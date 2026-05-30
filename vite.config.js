import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vite.dev/config/
export default defineConfig({
  plugins: [svelte()],
  build: {
    target: ['chrome80', 'firefox80', 'safari14', 'edge80'],
    cssTarget: ['chrome80', 'firefox80', 'safari14', 'edge80']
  }
})
