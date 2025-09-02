const path = require("path");
const fs = require("fs");
const MinCssExtractPlugin = require("mini-css-extract-plugin");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
const CopyWebpackPlugin = require("copy-webpack-plugin");

// HTMLファイルのパターンを取得
const htmlFiles = ["index.html", "consultant.html", "consultant_detail.html", "featured.html", "featured_detail.html", "useful.html", "useful_detail.html", "faq.html", "contact.html"];

// 英語版HTMLファイル
const enHtmlFiles = [];

const plugins = [
  new MinCssExtractPlugin({
    filename: "assets/css/style.css",
  }),
  new CopyWebpackPlugin({
    patterns: [
      {
        from: "src/assets/images",
        to: "assets/images",
        noErrorOnMissing: true,
        globOptions: {
          ignore: ["**/.DS_Store"],
        },
      },
      {
        from: "src/assets/images/mobile",
        to: "assets/images/mobile",
        noErrorOnMissing: true,
        globOptions: {
          ignore: ["**/.DS_Store"],
        },
      },
    ],
  }),
];

// 各HTMLファイルに対してプラグインを追加
htmlFiles.forEach((file) => {
  // src/ ディレクトリ内にファイルが存在するか確認
  const templatePath = path.resolve(__dirname, `src/${file}`);
  if (fs.existsSync(templatePath)) {
    plugins.push(
      new HtmlWebpackPlugin({
        template: `./src/${file}`,
        filename: `${file}`,
        inject: "body", // JSとCSSをbodyに挿入
        minify: {
          collapseWhitespace: false, // 常に改行を保持
          removeComments: process.env.NODE_ENV === "production",
          conservativeCollapse: true, // 複数の空白を一つにまとめるが、改行は保持
          preserveLineBreaks: true, // 改行を明示的に保持
        },
      }),
    );
  }
});

// 英語版HTMLファイルに対してプラグインを追加
enHtmlFiles.forEach((file) => {
  // src/en/ ディレクトリ内にファイルが存在するか確認
  const templatePath = path.resolve(__dirname, `src/en/${file}`);
  if (fs.existsSync(templatePath)) {
    plugins.push(
      new HtmlWebpackPlugin({
        template: `./src/en/${file}`,
        filename: `en/${file}`,
        inject: "body", // JSとCSSをbodyに挿入
        minify: {
          collapseWhitespace: false, // 常に改行を保持
          removeComments: process.env.NODE_ENV === "production",
          conservativeCollapse: true, // 複数の空白を一つにまとめるが、改行は保持
          preserveLineBreaks: true, // 改行を明示的に保持
        },
      }),
    );
  }
});

if (process.env.ANALYZE) {
  plugins.push(
    new BundleAnalyzerPlugin({
      analyzerMode: "static",
      reportFilename: "bundle-analysis.html",
      openAnalyzer: true,
      generateStatsFile: true,
      statsFilename: "stats.json",
    }),
  );
}

// SSI処理関数の定義（ログ表示は問題がある場合のみ）
function processSSI(content, filePath) {
  const currentDir = path.dirname(filePath);
  const docRoot = path.resolve(__dirname, "src");

  return content.replace(/<!--\s*#include\s+(virtual|file)=["'](.+?)["']\s*-->/g, (match, type, includePath) => {
    try {
      let includeFilePath;
      if (type === "virtual") {
        // 相対パスの処理
        let normalizedPath = includePath;
        if (normalizedPath.startsWith("./")) {
          normalizedPath = normalizedPath.slice(2);
        } else if (!normalizedPath.startsWith("/")) {
          normalizedPath = "/" + normalizedPath;
        }
        includeFilePath = path.resolve(docRoot, normalizedPath.replace(/^\//, ""));
      } else if (type === "file") {
        includeFilePath = path.resolve(currentDir, includePath);
      }

      if (fs.existsSync(includeFilePath)) {
        const includeContent = fs.readFileSync(includeFilePath, "utf8");
        // 再帰的に SSI を処理
        return processSSI(includeContent, includeFilePath);
      } else {
        // 代替パスを試す
        const altPath = path.resolve(docRoot, "includes", includePath.replace(/^\.\/?includes\//, ""));
        if (fs.existsSync(altPath)) {
          const includeContent = fs.readFileSync(altPath, "utf8");
          return processSSI(includeContent, altPath);
        }

        console.warn(`SSIファイルが見つかりませんでした: ${includePath}`);
        return `<!-- SSI include failed: ${includePath} -->`; // 失敗を示すコメント
      }
    } catch (err) {
      console.error(`SSI処理エラー (${type}=${includePath}): ${err.message}`);
      return `<!-- SSI include error: ${err.message} -->`; // エラーメッセージを含める
    }
  });
}

module.exports = {
  entry: "./src/assets/js/app.js",
  output: {
    path: path.resolve(__dirname, "dist"),
    filename: "assets/js/bundle.js",
    clean: {
      keep: /\.(php|jpg|jpeg|png|gif|svg|webp|ico)$/,
    },
  },
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "src"),
      "@assets": path.resolve(__dirname, "src/assets"),
      "@includes": path.resolve(__dirname, "src/includes"),
    },
  },
  devServer: {
    static: {
      directory: path.resolve(__dirname, "dist"),
      watch: {
        ignored: /node_modules/,
        poll: 1000, // ポーリング間隔を設定
      },
    },
    port: 8080,
    open: true,
    hot: true,
    compress: true,
    historyApiFallback: true,
    watchFiles: ["src/**/*.html", "src/en/**/*.html", "src/**/*.scss", "src/**/*.js"],
  },
  module: {
    rules: [
      {
        test: /\.html$/,
        use: [
          {
            loader: "html-loader",
            options: {
              sources: true,
              minimize: false, // HTMLの圧縮を無効化
              preprocessor: (content, loaderContext) => {
                const processedContent = processSSI(content, loaderContext.resourcePath);

                // 未処理のSSI命令チェック（問題がある場合のみ表示）
                if (processedContent.includes("<!--#include")) {
                  console.error(`[SSI WARNING] 未処理のSSI命令が残っています: ${loaderContext.resourcePath}`);
                  const matches = processedContent.match(/<!--\s*#include\s+(virtual|file)=["'](.+?)["']\s*-->/g);
                  if (matches) {
                    matches.forEach((match) => console.error(`  未処理: ${match}`));
                  }
                }

                return processedContent;
              },
            },
          },
        ],
      },
      {
        test: /\.css$/i,
        include: path.resolve(__dirname, "src"),
        use: [{ loader: MinCssExtractPlugin.loader, options: { publicPath: "../../../" } }, "css-loader", "postcss-loader"],
      },
      {
        test: /\.(scss|sass)$/i,
        use: [{ loader: MinCssExtractPlugin.loader, options: { publicPath: "../../../" } }, "css-loader", "postcss-loader", "sass-loader"],
      },
      {
        test: /node_modules\/(.+)\.css$/,
        use: [
          { loader: MinCssExtractPlugin.loader, options: { publicPath: "../../../" } },
          { loader: "css-loader", options: { url: false } },
        ],
        sideEffects: true,
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: "asset/resource",
        generator: {
          filename: "assets/fonts/[name][ext]",
        },
      },
      {
        test: /\.(ico|png|jpg|jpeg|gif|svg|webp)$/i,
        type: "asset/resource",
        generator: {
          filename: "assets/images/[name][ext]",
        },
      },
    ],
  },
  plugins: plugins,
  optimization: {
    splitChunks: false,
    runtimeChunk: false,
    minimize: process.env.NODE_ENV === "production", // productionモードのみ圧縮
    usedExports: true,
    sideEffects: true,
  },
};
