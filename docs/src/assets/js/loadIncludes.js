async function loadIncludes() {
  const includeElements = document.querySelectorAll('[data-include]');
  
  for (const element of includeElements) {
    const file = element.getAttribute('data-include');
    try {
      const response = await fetch(`./includes/${file}`);
      if (response.ok) {
        const content = await response.text();
        element.innerHTML = content;
      }
    } catch (error) {
      console.error(`Failed to load ${file}:`, error);
    }
  }
  
  const event = new Event('includesLoaded');
  document.dispatchEvent(event);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', loadIncludes);
} else {
  loadIncludes();
}
