const fs = require('fs');
const path = require('path');

const oksDir = path.join(__dirname, '..', 'oks');

function findPhpFiles(dir) {
  const files = [];
  
  function traverse(currentPath) {
    const items = fs.readdirSync(currentPath);
    
    items.forEach(item => {
      const fullPath = path.join(currentPath, item);
      const stat = fs.statSync(fullPath);
      
      if (stat.isDirectory() && item !== 'node_modules' && item !== 'acf-json') {
        traverse(fullPath);
      } else if (stat.isFile() && /\.php$/i.test(item)) {
        files.push(fullPath);
      }
    });
  }
  
  traverse(dir);
  return files;
}

function convertImgToPicture(content) {
  const imgRegex = /<img\s+([^>]*src=["']<\?php\s+echo\s+get_template_directory_uri\(\);\s*\?\>\/dist\/assets\/images\/[^"']*\.jpg[^"']*["'][^>]*)>/gi;
  
  return content.replace(imgRegex, (match, attributes) => {
    const srcMatch = attributes.match(/src=["']<\?php\s+echo\s+get_template_directory_uri\(\);\s*\?\>(\/dist\/assets\/images\/[^"'?]+\.jpg)(\?[^"']*)?["']/i);
    if (!srcMatch) return match;
    
    const imagePath = srcMatch[1];
    const queryString = srcMatch[2] || '';
    
    const altMatch = attributes.match(/alt=["']([^"']*)["']/i);
    const alt = altMatch ? altMatch[1] : '';
    
    const classMatch = attributes.match(/class=["']([^"']*)["']/i);
    const classAttr = classMatch ? ` class="${classMatch[1]}"` : '';
    
    const webpPath = imagePath.replace(/\.jpg$/i, '.webp');
    const mobilePath = imagePath.replace(/\.jpg$/i, '_md.webp');
    
    return `<picture>
              <source srcset="<?php echo get_template_directory_uri(); ?>${mobilePath}" media="(max-width: 1119px)" type="image/webp" />
              <source srcset="<?php echo get_template_directory_uri(); ?>${webpPath}" type="image/webp" />
              <img alt="${alt}" src="<?php echo get_template_directory_uri(); ?>${imagePath}${queryString}"${classAttr} loading="lazy" />
            </picture>`;
  });
}

function processPhpFile(filePath) {
  try {
    const content = fs.readFileSync(filePath, 'utf8');
    const originalContent = content;
    
    const newContent = convertImgToPicture(content);
    
    if (originalContent !== newContent) {
      fs.writeFileSync(filePath, newContent, 'utf8');
      console.log(`✓ Updated: ${path.relative(oksDir, filePath)}`);
      return true;
    } else {
      console.log(`- No changes: ${path.relative(oksDir, filePath)}`);
      return false;
    }
  } catch (error) {
    console.error(`✗ Failed: ${path.relative(oksDir, filePath)}`);
    console.error(error.message);
    return false;
  }
}

console.log('Starting PHP img to picture conversion...\n');

const phpFiles = findPhpFiles(oksDir);

if (phpFiles.length === 0) {
  console.log('No PHP files found.');
  process.exit(0);
}

console.log(`Found ${phpFiles.length} PHP files\n`);

let updatedCount = 0;
phpFiles.forEach(file => {
  if (processPhpFile(file)) {
    updatedCount++;
  }
});

console.log(`\nConversion complete! ${updatedCount} files updated.`);
