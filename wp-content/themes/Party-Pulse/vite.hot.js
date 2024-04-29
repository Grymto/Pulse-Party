const fs = require('fs');
const path = require('path');

// Funktion för att skapa "hot"-filen
function createHotFile() {
    // Få den absoluta sökvägen för output-mappen
    const buildPath = path.resolve(__dirname, "build");

    // Skapa "hot"-filen i output-mappen
    const hotFilePath = path.join(buildPath, 'hot');
    const port = 5173;

    fs.writeFileSync(hotFilePath, 'http://[::1]:' + port);
    console.log(`"hot" file created at ${hotFilePath}`);
}

function deleteHotFile() {
    // Få den absoluta sökvägen för output-mappen
    const buildPath = path.resolve(__dirname, "build");

    // Skapa "hot"-filen i output-mappen
    const hotFilePath = path.join(buildPath, 'hot');

    fs.unlink(hotFilePath, function(){
        console.log(`"hot" file deleted at ${hotFilePath}`);
    });
}

module.exports = {
    createHotFile: createHotFile,
    deleteHotFile: deleteHotFile
};