import fs from 'fs';
import laravel from 'laravel-vite-plugin';
import {
    defineConfig
} from 'vite';

const populateFiles = async (srcDir, type) => {
    // Variables
    let isFolder = false;
    let folderPath = null;
    let filePath = null;
    let populate = [];

    // Membaca directory
    fs.readdirSync(srcDir).forEach(async file => {
        // Reset variables
        isFolder = false;
        folderPath = "";
        filePath = `${srcDir}/${file}`;

        const fileArr = file.split("."); // Split berdasarkan . untuk mengambil extensi
        isFolder = fileArr[fileArr.length - 1] !== type; // Jika extensi tidak sama dengan type maka filePath adalah folder

        // Jika folder isi folderPath
        if (isFolder) {
            folderPath = filePath;
        }

        // Jika bukan folder maka execute
        if (isFolder === false) {
            populate.push(filePath);
        } else {
            const fileJs = await populateFiles(folderPath, type);
            for (const file of fileJs) {
                populate.push(file);
            }
        }
    })

    return populate;
}

const inputFiles = async function () {
    const jsPageFiles = await populateFiles('resources/js', 'js');
    const cssPageFiles = await populateFiles('resources/css', 'css');
    return [...cssPageFiles, ...jsPageFiles];
}

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/vite.hot',
            buildDirectory: 'assets/pages',
            input: await inputFiles(),
            refresh: [{
                paths: ['resources/css/**', 'resources/js/**', 'resources/views/**'],
            }],
        }),
    ],
    build: {
        manifest: 'assets.json',
        minify: true,
        cssMinify: true
    },
});
