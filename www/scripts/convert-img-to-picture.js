const fs = require('fs');
const path = require('path');

const srcDir = path.join(__dirname, '..', 'src');

function findHtmlFiles(dir) {
  const files = [];
  
  function traverse(currentPath) {
    const items = fs.readdirSync(currentPath);
    
    items.forEach(item => {
      const fullPath = path.join(currentPath, item);
      const stat = fs.statSync(fullPath);
      
      if (stat.isDirectory() && item !== 'node_modules') {
        traverse(fullPath);
      } else if (stat.isFile() && /\.html$/i.test(item)) {
        files.push(fullPath);
      }
    });
  }
  
  traverse(dir);
  return files;
}

function convertImgToPicture(content) {
  const imgRegex = /<img\s+([^>]*src=["']([^"']*\.jpg[^"']*)["'][^>]*)>/gi;
  
  return content.replace(imgRegex, (match, attributes, src) => {
    const altMatch = attributes.match(/alt=["']([^"']*)["']/i);
    const alt = altMatch ? altMatch[1] : '';
    
    const loadingMatch = attributes.match(/loading=["']([^"']*)["']/i);
    const loading = loadingMatch ? ` loading="${loadingMatch[1]}"` : ' loading="lazy"';
    
    const classMatch = attributes.match(/class=["']([^"']*)["']/i);
    const classAttr = classMatch ? ` class="${classMatch[1]}"` : '';
    
    const srcClean = src.replace(/\?.*$/, '');
    const webpSrc = srcClean.replace(/\.jpg$/i, '.webp');
    
    const mobileSrc = srcClean.replace(/\.jpg$/i, '_md.webp');
    
    return `<picture>
              <source srcset="${mobileSrc}" media="(max-width: 1119px)" type="image/webp" />
              <source srcset="${webpSrc}" type="image/webp" />
              <img alt="${alt}" src="${srcClean}"${classAttr}${loading} />
            </picture>`;
  });
}

function processHtmlFile(filePath) {
  try {
    const content = fs.readFileSync(filePath, 'utf8');
    const originalContent = content;
    
    const newContent = convertImgToPicture(content);
    
    if (originalContent !== newContent) {
      fs.writeFileSync(filePath, newContent, 'utf8');
      console.log(`✓ Updated: ${path.relative(srcDir, filePath)}`);
      return true;
    } else {
      console.log(`- No changes: ${path.relative(srcDir, filePath)}`);
      return false;
    }
  } catch (error) {
    console.error(`✗ Failed: ${path.relative(srcDir, filePath)}`);
    console.error(error.message);
    return false;
  }
}

console.log('Starting HTML img to picture conversion...\n');

const htmlFiles = findHtmlFiles(srcDir);

if (htmlFiles.length === 0) {
  console.log('No HTML files found.');
  process.exit(0);
}

console.log(`Found ${htmlFiles.length} HTML files\n`);

let updatedCount = 0;
htmlFiles.forEach(file => {
  if (processHtmlFile(file)) {
    updatedCount++;
  }
});

console.log(`\nConversion complete! ${updatedCount} files updated.`);
