window.onload = function() {
    const ui = SwaggerUIBundle({
        url: "./openapi.json",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
        plugins: [SwaggerUIBundle.plugins.DownloadUrl],
        layout: "StandaloneLayout"
    })
    window.ui = ui
    
    const nav = document.querySelectorAll("nav li")
    for (const li of nav) {
        li.addEventListener('click', function(e) {
            const isActive = document.querySelector("nav li.active")
            if (isActive) {
                isActive.classList.remove('active')
            }
            e.target.classList.add('active')
      })
    }
}