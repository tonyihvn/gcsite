/* Admin Panel JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm bulk actions
    const bulkActionForm = document.querySelector('[data-action="bulk"]');
    if (bulkActionForm) {
        bulkActionForm.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to perform this action on selected items?')) {
                e.preventDefault();
            }
        });
    }

    // Replace all onclick confirm with SweetAlert
    document.querySelectorAll('[onclick*="confirm"]').forEach(function(el) {
        const onclickAttr = el.getAttribute('onclick');
        const messageMatch = onclickAttr.match(/confirm\('([^']*)'\)/);
        if (messageMatch) {
            const message = messageMatch[1];
            const actionMatch = onclickAttr.match(/return\s+([^;]*);/);
            const actionCode = actionMatch ? actionMatch[1] : 'true';
            
            el.removeAttribute('onclick');
            el.addEventListener('click', function(e) {
                e.preventDefault();
                confirmDelete(message, actionCode, el);
            });
        }
    });
});

// SweetAlert confirmation for delete actions
function confirmDelete(message = 'Are you sure?', actionCode = 'true', element = null) {
    Swal.fire({
        title: 'Confirm Action',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            if (element && element.tagName === 'FORM') {
                element.submit();
            } else if (element && element.tagName === 'A') {
                window.location.href = element.href;
            } else if (actionCode === 'true') {
                return true;
            } else {
                eval(actionCode);
            }
        }
    });
}

// SweetAlert confirmation for form submissions
function confirmFormDelete(form, message = 'Are you sure?') {
    Swal.fire({
        title: 'Confirm Deletion',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed && form) {
            form.submit();
        }
    });
    return false;
}

// Global success alert
function showSuccess(message = 'Success!') {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

// Global error alert
function showError(message = 'Error occurred!') {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: '#0369a1'
    });
}

// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('open');
}

// Search/Filter table
function filterTable(searchInput) {
    const input = document.getElementById(searchInput);
    const filter = input.value.toUpperCase();
    const table = input.closest('.card').querySelector('.table');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        let found = false;
        const cells = row.getElementsByTagName('td');

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell.textContent.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

// Edit form population
function editItem(itemId, endpoint) {
    fetch(`${endpoint}/${itemId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            Object.keys(data).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = data[key];
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

// Delete confirmation
function deleteConfirm(itemName = 'this item') {
    return confirm(`Are you sure you want to delete ${itemName}?`);
}

// Export to CSV
function exportTableToCSV(filename = 'export.csv') {
    const table = document.querySelector('.table');
    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td, th');
        const rowData = Array.from(cells).map(cell => {
            return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
        });
        csv.push(rowData.join(','));
    });

    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const link = document.createElement('a');
    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    link.download = filename;
    link.click();
}
