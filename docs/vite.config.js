import { defineConfig } from 'vite'
import { resolve } from 'path'
import { glob } from 'glob'
import { viteStaticCopy } from 'vite-plugin-static-copy'
import { readFileSync } from 'fs'
import { fileURLToPath } from 'url'
import { dirname, join } from 'path'

const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)

const htmlFiles = Object.fromEntries(
  glob.sync('src/**/*.html')
    .filter(file => !file.includes('/includes/'))
    .map(file => [
      file.slice(4, -5),
      resolve(__dirname, file)
    ])
)

function htmlIncludePlugin() {
  return {
    name: 'html-include',
    transformIndexHtml: {
      order: 'pre',
      handler(html, ctx) {
        return html.replace(
          /<div[^>]*data-include="([^"]+)"[^>]*><\/div>/g,
          (match, includePath) => {
            const srcDir = resolve(__dirname, 'src')
            const fullPath = join(srcDir, 'includes', includePath)
            try {
              const content = readFileSync(fullPath, 'utf-8')
              return content
            } catch (e) {
              console.warn(`Could not include ${includePath}: ${e.message}`)
              return match
            }
          }
        )
      }
    }
  }
}

export default defineConfig({
  base: './',
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
          return 'assets/[name].[hash][extname]'
        },
        entryFileNames: 'assets/js/[name].[hash].js',
        chunkFileNames: 'assets/js/[name].[hash].js'
      }
    }
  },
  plugins: [
    htmlIncludePlugin(),
    viteStaticCopy({
      targets: [
        {
          src: 'assets/img/*',
          dest: 'assets/img'
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