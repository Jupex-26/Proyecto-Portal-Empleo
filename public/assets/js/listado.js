document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('size');
  const link = document.getElementById('link-size');

  if (select && link) {
    select.addEventListener('change', () => {
      const selectedOption = select.options[select.selectedIndex];
      const newSize = selectedOption.dataset.size;

      // Solo reemplaza el valor del parámetro "size"
      link.href = link.href.replace(/(size=)[^&]*/, `$1${newSize}`);
    });
  }
});
