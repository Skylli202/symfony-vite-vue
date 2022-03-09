import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  root: './assets',
  base: '/assets/',
  server: {
    https: true
  },
  build: {
    manifest: true,
    assetsDir: '',
    outDir: '../public/build',
    rollupOptions: {
      input: {
        'main.js': './assets/main.js'
      }
    },
  }
})
