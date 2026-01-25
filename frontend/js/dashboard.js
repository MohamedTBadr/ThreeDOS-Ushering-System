// Configuration
const API_URL = '../backend/api.php';
const TOKEN = localStorage.getItem('usher_token');

// Redirect if not logged in
if (!TOKEN) window.location.href = 'login.html';

// State
let currentPage = 1;
let totalPages = 1;
let userRole = localStorage.getItem('user_role');
let currentFilters = { search: '', level: '', rating: '' };

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.logo h2').innerText = `ThreeDOS'26`;
    loadApplicants();
        updateStatistics();
    setupEventListeners();
});

// Setup Event Listeners
function setupEventListeners() {
    let searchTimeout;
    document.getElementById('search').addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value.trim();
            currentPage = 1;
            loadApplicants();
        }, 500);
    });

    document.getElementById('level-filter').addEventListener('change', (e) => {
        currentFilters.level = e.target.value;
        currentPage = 1;
        loadApplicants();
    });

    document.getElementById('rating-filter').addEventListener('change', (e) => {
        currentFilters.rating = e.target.value;
        currentPage = 1;
        loadApplicants();
    
    });
}

// Load Applicants
async function loadApplicants() {
    const tbody = document.getElementById('applicants-tbody');
    tbody.innerHTML = '<tr><td colspan="9">Loading applicants...</td></tr>';

    try {
        let url = `${API_URL}?page=${currentPage}&limit=200`;
        if (currentFilters.search) url += `&search=${encodeURIComponent(currentFilters.search)}`;
        if (currentFilters.level) url += `&level=${encodeURIComponent(currentFilters.level)}`;
        if (currentFilters.rating) url += `&rating=${encodeURIComponent(currentFilters.rating)}`;

        const response = await fetch(url, { headers: { 'X-Token': TOKEN } });
        const result = await response.json();

        if (result.status === 'success') {
            displayApplicants(result.data.applicants);
            updatePagination(result.data.page, result.data.total_pages);
            
        } else {
            if (response.status === 401) window.location.href = 'login.html';
            showToast(result.message, 'error');
            tbody.innerHTML = '<tr><td colspan="9">No data</td></tr>';
        }
    } catch (error) {
        showToast('Failed to load data', 'error');
        tbody.innerHTML = '<tr><td colspan="9">Error loading data</td></tr>';
    }
}

// Display Applicants in Table
function displayApplicants(applicants) {
    const tbody = document.getElementById('applicants-tbody');

    if (!applicants || applicants.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9">No applicants found</td></tr>';
        return;
    }

    tbody.innerHTML = applicants.map(app => `
        <tr>
            <td>${escapeHtml(app.name)}</td>
            <td>${escapeHtml(app.email)}</td>
            <td>${escapeHtml(app.phone)}</td>
            <td>${escapeHtml(app.college)}</td>
            <td>${escapeHtml(app.level)}</td>
            <td>${escapeHtml(app.council || '')}</td>
            <td><span class="rating-badge rating-${getRatingClass(app.rating)}">${app.rating || 'Pending'}</span></td>
            <td>${escapeHtml(app.interviewed_by || 'NA')}</td>

            <td>
                <button class="btn-primary" onclick="redirectEdit(${app.id})">✏️ Edit</button>
            </td>
        </tr>
    `).join('');
}

// Redirect to edit page
function redirectEdit(id) {
    window.location.href = `applicant_details_page.html?id=${id}`;
}

// Update Statistics (Quick Stats from backend)
async function updateStatistics() {
    try {
        const response = await fetch(`${API_URL}?quickstats=1`, {
            headers: { 'X-Token': TOKEN }
        });
        const result = await response.json();

        if (result.status === 'success') {
            const stats = result.data;

            document.getElementById('stat-total').innerText = stats.total;
            document.getElementById('stat-accepted').innerText = stats.accepted;
            document.getElementById('stat-backup').innerText = stats.backup;
            document.getElementById('stat-rejected').innerText = stats.rejected;
            document.getElementById('stat-pending').innerText = stats.pending;
        } else {
            console.error('Failed to fetch quick stats:', result.message);
        }
    } catch (error) {
        console.error('Error fetching quick stats:', error);
    }
}


// Pagination
function updatePagination(page, total) {
    currentPage = page;
    totalPages = total;
    document.getElementById('page-info').innerText = `Page ${currentPage} of ${totalPages}`;
}

function nextPage() {
    if (currentPage < totalPages) { currentPage++; loadApplicants(); }
}
function previousPage() {
    if (currentPage > 1) { currentPage--; loadApplicants(); }
}

// Clear Filters
function clearFilters() {
    currentFilters = { search: '', level: '', rating: '' };
    document.getElementById('search').value = '';
    document.getElementById('level-filter').value = '';
    document.getElementById('rating-filter').value = '';
    currentPage = 1;
    loadApplicants();
}

// Helpers
function getRatingClass(rating) {
    const map = { 'Pending':'pending', 'Acceptance':'acceptance', 'B':'b', 'Rejection':'rejection' };
    return map[rating] || 'pending';
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showToast(message, type='info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}

// Delete Applicant
async function deleteApplicant(id) {
    if (!confirm('Are you sure?')) return;

    try {
        const response = await fetch(API_URL, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-Token': TOKEN },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.status === 'success') { showToast('Deleted', 'success'); loadApplicants(); }
        else showToast(result.message, 'error');
    } catch (error) { showToast('Failed to delete', 'error'); }
}
