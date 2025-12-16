/**
 * このスクリプトはレスポンシブ用の小サイズ画像を一括生成します
 * 使い方:
 * 1. npm install sharp fs-extra path glob
 * 2. node scripts/generate-responsive-images.js
 */

const sharp = require("sharp");
const fs = require("fs-extra");
const path = require("path");
const glob = require("glob");

// デバッグモード
// trueにすると詳細なログが出力されます
const DEBUG = false;

// 設定
const config = {
  // 入力ディレクトリ（元画像が置かれている場所）
  inputDir: "./src/assets/images",

  // 処理する画像の拡張子（jpg, png）
  extensions: ["jpg", "jpeg", "png"],

  // サイズプリセット（_md用）
  sizes: [
    {
      suffix: "md",
      width: 1000,
      quality: 85,
    },
  ],
};

// デバッグ用ログ関数
function debug(message) {
  if (DEBUG) {
    console.log(`[DEBUG] ${message}`);
  }
}

// 既存のwebpファイルを削除
function deleteExistingWebpFiles() {
  const webpFiles = glob.sync(`${config.inputDir}/**/*.webp`);
  console.log(`既存の.webpファイルを削除中: ${webpFiles.length}ファイル`);

  webpFiles.forEach((file) => {
    if (fs.statSync(file).isFile()) {
      fs.unlinkSync(file);
      debug(`ファイルを削除しました: ${file}`);
    }
  });

  console.log(`.webpファイルの削除が完了しました`);
}

// 全ファイルリストから処理するファイル情報を取得
function getAllImageInfo() {
  const sourceFiles = getInputFiles();
  console.log(`ソース画像: ${sourceFiles.length}ファイル見つかりました`);

  // 各ソースファイルに対して生成すべきサイズごとのターゲットファイルを計算
  const allImageInfo = [];

  sourceFiles.forEach((sourceFile) => {
    const { dir, name, ext } = path.parse(sourceFile);
    const extension = ext.replace(".", "");

    // ソースディレクトリからの相対パスを取得
    const relativePath = path.relative(config.inputDir, dir);
    debug(`相対パス: ${relativePath} ファイル: ${name}${ext}`);

    config.sizes.forEach((size) => {
      // WebP形式を生成（元がWebPでない場合）
      if (extension !== "webp") {
        const webpFilename = `${name}_${size.suffix}.webp`;
        const webpOutputPath = path.join(dir, webpFilename);

        allImageInfo.push({
          sourceFile,
          outputPath: webpOutputPath,
          size,
          extension,
          format: "webp", // WebP形式で出力
        });
      }
    });

    // フルサイズのWebPも生成（元がWebPでない場合）
    if (extension !== "webp") {
      const webpFilename = `${name}.webp`;
      const webpOutputPath = path.join(dir, webpFilename);

      allImageInfo.push({
        sourceFile,
        outputPath: webpOutputPath,
        size: { width: null, quality: 90 }, // フルサイズ
        extension,
        format: "webp",
      });
    }
  });

  return allImageInfo;
}

// メイン処理
async function processImages() {
  // 既存の.webpファイルを削除
  deleteExistingWebpFiles();

  // 全ての画像情報を取得
  const allImageInfo = getAllImageInfo();

  // 処理結果の統計
  let stats = {
    total: allImageInfo.length,
    processed: 0,
    errors: 0,
  };

  console.log(`処理予定の画像: ${stats.total}ファイル`);

  // 全ての画像を処理
  for (const info of allImageInfo) {
    console.log(`処理中: ${info.sourceFile} → ${info.outputPath}`);

    try {
      const originalSize = getFileSizeInKB(info.sourceFile);

      // 出力ディレクトリを確認
      const outputDir = path.dirname(info.outputPath);
      fs.ensureDirSync(outputDir);

      // 画像のリサイズ（フルサイズの場合はリサイズしない）
      let sharpInstance = sharp(info.sourceFile);
      
      if (info.size.width) {
        sharpInstance = sharpInstance.resize({
          width: info.size.width,
          fit: "inside",
          withoutEnlargement: true,
        });
      }

      // 出力形式に応じた処理
      if (info.format === "webp") {
        // WebP形式で出力
        await sharpInstance.webp({ quality: info.size.quality }).toFile(info.outputPath);
      } else if (["jpg", "jpeg"].includes(info.format)) {
        await sharpInstance.jpeg({ quality: info.size.quality }).toFile(info.outputPath);
      } else if (info.format === "png") {
        await sharpInstance.png({ quality: info.size.quality }).toFile(info.outputPath);
      }

      // 結果の確認
      if (fs.existsSync(info.outputPath)) {
        const newSize = getFileSizeInKB(info.outputPath);
        console.log(`  完了: ${originalSize}KB → ${newSize}KB (${Math.round((newSize / originalSize) * 100)}%)`);
        stats.processed++;
      } else {
        console.error(`  エラー: ファイルが生成されませんでした: ${info.outputPath}`);
        stats.errors++;
      }
    } catch (err) {
      console.error(`  エラー: ${err.message}`);
      stats.errors++;
    }
  }

  // 処理結果のサマリー
  console.log("\n処理結果サマリー:");
  console.log(`  合計ファイル数: ${stats.total}`);
  console.log(`  処理済み: ${stats.processed}`);
  console.log(`  エラー: ${stats.errors}`);

  return stats;
}

// ファイルサイズをKB単位で取得
function getFileSizeInKB(filePath) {
  const stats = fs.statSync(filePath);
  return Math.round(stats.size / 1024);
}

// 元画像を検索
function getInputFiles() {
  const patterns = config.extensions.map((ext) => `${config.inputDir}/**/*.${ext}`);
  let files = [];

  patterns.forEach((pattern) => {
    const matches = glob.sync(pattern);
    files = files.concat(matches);
  });

  return files;
}

// 処理結果を記録する関数
function logResults(stats) {
  const timestamp = new Date().toISOString();
  console.log(`${timestamp} - 処理結果: 合計: ${stats.total}, 処理済み: ${stats.processed}, エラー: ${stats.errors}`);
}

// 確認プロンプトを表示する関数
async function confirmExecution() {
  const readline = require("readline").createInterface({
    input: process.stdin,
    output: process.stdout,
  });

  return new Promise((resolve) => {
    readline.question(
      `レスポンシブ画像生成スクリプト\n元画像サーチパス: ${config.inputDir}\n\n警告: 実行すると既存の.webpファイルは全て削除されます。\n続行しますか？ (y/n): `,
      (answer) => {
        readline.close();
        resolve(answer.toLowerCase() === "y");
      },
    );
  });
}

// スクリプト実行
async function main() {
  // 実行前に確認
  const proceed = await confirmExecution();
  if (!proceed) {
    console.log("処理をキャンセルしました。");
    return;
  }

  try {
    const stats = await processImages();
    logResults(stats);
    console.log("すべての画像の処理が完了しました！");
  } catch (err) {
    console.error("エラーが発生しました:", err);
  }
}

main();
