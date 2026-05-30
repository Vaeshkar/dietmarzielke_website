import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

// Simulates the save_content.php backend in local Vite development
function localCmsPlugin() {
  return {
    name: 'local-cms-plugin',
    configureServer(server) {
      server.middlewares.use(async (req, res, next) => {
        if (req.url === '/save_content.php' && req.method === 'POST') {
          try {
            const buffers = []
            for await (const chunk of req) {
              buffers.push(chunk)
            }
            const body = JSON.parse(Buffer.concat(buffers).toString())
            const { password, content } = body

            // Check password (default: dietmar123)
            if (password === 'dietmar123') {
              const filePath = path.resolve(__dirname, 'public/content.json')
              fs.writeFileSync(filePath, JSON.stringify(content, null, 2), 'utf-8')
              res.writeHead(200, { 'Content-Type': 'application/json' })
              res.end(JSON.stringify({ success: true, message: 'Inhalt erfolgreich lokal in public/content.json gespeichert!' }))
            } else {
              res.writeHead(401, { 'Content-Type': 'application/json' })
              res.end(JSON.stringify({ success: false, message: 'Falsches Passwort (für lokale Entwicklung: "dietmar123")' }))
            }
          } catch (err) {
            res.writeHead(500, { 'Content-Type': 'application/json' })
            res.end(JSON.stringify({ success: false, message: 'Serverfehler: ' + err.message }))
          }
          return
        }
        next()
      })
    }
  }
}

// https://vite.dev/config/
export default defineConfig({
  plugins: [svelte(), localCmsPlugin()],
  build: {
    target: ['chrome80', 'firefox80', 'safari14', 'edge80'],
    cssTarget: ['chrome80', 'firefox80', 'safari14', 'edge80']
  }
})
