1. Install NodeJs (version 18 and above is recommended)

2. Place files and folders in wordpress theme (/wp-content/themes/mytheme)

3. Add "require_once("Vite.php");" to your theme's function.php

5. Open theme location in terminal

6. Run "npm install"

Done!

- In terminal to build js and scss: "npm run build"
Results end up in "/wp-content/themes/mytheme/build/assets"

- In terminal to run Vite server with hot reloading: "npm run dev"