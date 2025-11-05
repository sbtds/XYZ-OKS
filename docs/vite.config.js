import { defineConfig } from 'vite'
import { resolve } from 'path'
import { glob } from 'glob'
import { viteStaticCopy } from 'vite-plugin-static-copy'

// Get all HTML files
const htmlFiles = Object.fromEntries(
  glob.sync('src/**/*.html').map(file => [
    file.slice(4, -5), // Remove 'src/' and '.html'
    resolve(__dirname, file)
  ])
)

export default defineConfig({
  root: 'src',
  publicDir: false,
  build: {
    outDir: '../dist',
    emptyOutDir: true,
    rollupOptions: {
      input: htmlFiles,
      output: {
        assetFileNames: (assetInfo) => {
          if (/\.(gif|jpe?g|png|svg|webp)$/i.test(assetInfo.name ?? '')) {
            return 'assets/img/[name][extname]'
          }
          if (/\.css$/i.test(assetInfo.name ?? '')) {
            return 'assets/css/[name].[hash][extname]'
          }
          if (/\.js$/i.test(assetInfo.name ?? '')) {
            return 'assets/js/[name].[hash][extname]'
          }
          return 'assets/[name].[hash][extname]'
        }
      }
    }
  },
  plugins: [
    viteStaticCopy({
      targets: [
        {
          src: 'assets/img/*',
          dest: 'assets/img'
        },
        {
          src: 'assets/js/*.js',
          dest: 'assets/js'
        }
      ]
    })
  ],
  server: {
    open: true,
    watch: {
      usePolling: true
    }
  }
})