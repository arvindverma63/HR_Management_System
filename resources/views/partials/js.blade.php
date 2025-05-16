 <script>
    // Sidebar toggle for mobile
    const openSidebarBtn = document.getElementById('open-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');

    openSidebarBtn.addEventListener('click', () => {
      sidebar.classList.add('open');
    });

    closeSidebarBtn.addEventListener('click', () => {
      sidebar.classList.remove('open');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !openSidebarBtn.contains(e.target) && sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
      }
    });

    // Client-side search
    document.getElementById('search').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#attendance-table tr');
      rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const dept = row.cells[1].textContent.toLowerCase();
        row.style.display = (name.includes(searchTerm) || dept.includes(searchTerm)) ? '' : 'none';
      });
    });

    // Sorting function
    function sortTable(columnIndex) {
      const table = document.getElementById('attendance-table');
      const rows = Array.from(table.rows);
      const isAscending = table.dataset.sortOrder !== 'asc';
      table.dataset.sortOrder = isAscending ? 'asc' : 'desc';

      rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.toLowerCase();
        const bValue = b.cells[columnIndex].textContent.toLowerCase();
        return isAscending ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
      });

      table.innerHTML = '';
      rows.forEach(row => table.appendChild(row));
    }
  </script>
