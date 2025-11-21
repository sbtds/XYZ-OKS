const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const sourceDir = path.join(__dirname, '..', 'src', 'assets', 'images');

function findJpgFiles(dir) {
  const files = [];
  
  function traverse(currentPath) {
    const items = fs.readdirSync(currentPath);
    
    items.forEach(item => {
      const fullPath = path.join(currentPath, item);
      const stat = fs.statSync(fullPath);
      
      if (stat.isDirectory()) {
        traverse(fullPath);
      } else if (stat.isFile() && /\.jpe?g$/i.test(item)) {
        files.push(fullPath);
      }
    });
  }
  
  traverse(dir);
  return files;
}

function convertToWebP(jpgPath) {
  const webpPath = jpgPath.replace(/\.jpe?g$/i, '.webp');
  const mobileWebpPath = jpgPath.replace(/\.jpe?g$/i, '_md.webp');
  
  try {
    console.log(`Converting: ${path.relative(sourceDir, jpgPath)}`);
    
    execSync(`cwebp -q 80 "${jpgPath}" -o "${webpPath}"`, { stdio: 'inherit' });
    console.log(`✓ Created: ${path.relative(sourceDir, webpPath)}`);
    
    execSync(`cwebp -q 80 -resize 0 750 "${jpgPath}" -o "${mobileWebpPath}"`, { stdio: 'inherit' });
    console.log(`✓ Created: ${path.relative(sourceDir, mobileWebpPath)}`);
  } catch (error) {
    console.error(`✗ Failed to convert: ${jpgPath}`);
    console.error(error.message);
  }
}

console.log('Starting JPG to WebP conversion...\n');

const jpgFiles = findJpgFiles(sourceDir);

if (jpgFiles.length === 0) {
  console.log('No JPG files found.');
  process.exit(0);
}

console.log(`Found ${jpgFiles.length} JPG files\n`);

jpgFiles.forEach(convertToWebP);

console.log('\nConversion complete!');
