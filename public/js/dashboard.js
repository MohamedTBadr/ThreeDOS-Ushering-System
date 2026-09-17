// Configuration
const API_URL = '/api/registrations';
const TOKEN = localStorage.getItem('usher_token');

// Redirect if not logged in
if (!TOKEN) window.location.href = 'register.html';

// State
let currentCursor = null;
let prevCursor = null;
let hasMore = false;
let userRole = localStorage.getItem('user_role');
let currentFilters = { search: '', level: '', rating: '', event_type: '' };

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    // Safety fallback: Hide loader after 10s max (in case of data hang)
    const safetyTimer = setTimeout(hideGlobalLoader, 10000);

    try {
        const logo = document.querySelector('.logo h2');
        if (logo) logo.innerText = `ThreeDOS'26`;
        
        // Initial Load
        await Promise.all([loadApplicants(), updateStatistics()]);
    } catch (e) {
        console.error("Init Error:", e);
    } finally {
        clearTimeout(safetyTimer);
        // Hide Global Loader after data is ready (or optional error)
        hideGlobalLoader();
        setupEventListeners();
    }
});

function hideGlobalLoader() {
    const globalLoader = document.getElementById('global-loader');
    if (globalLoader && !globalLoader.classList.contains('hidden')) {
        globalLoader.classList.add('hidden');
        setTimeout(() => globalLoader.style.display = 'none', 500);
    }
}

// Setup Event Listeners
function setupEventListeners() {
    let searchTimeout;
    document.getElementById('search').addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value.trim();
            currentCursor = null; // Reset cursor on filter change
            loadApplicants();
        }, 500);
    });

    document.getElementById('level-filter').addEventListener('change', (e) => {
        currentFilters.level = e.target.value;
        currentCursor = null; // Reset cursor on filter change
        loadApplicants();
    });

    document.getElementById('rating-filter').addEventListener('change', (e) => {
        currentFilters.rating = e.target.value;
        currentCursor = null; // Reset cursor on filter change
        loadApplicants();
    });

    document.getElementById('event-type-filter').addEventListener('change', (e) => {
        currentFilters.event_type = e.target.value;
        currentCursor = null; // Reset cursor on filter change
        loadApplicants();
    });
}

// Load Applicants
async function loadApplicants(usePrevCursor = false) {
    const tbody = document.getElementById('applicants-tbody');
    tbody.innerHTML = '<tr><td colspan="9">Loading applicants...</td></tr>';

    try {
        let url = `${API_URL}?limit=25`;
        
        // Add cursor for pagination
        if (usePrevCursor && prevCursor) {
            url += `&prev_cursor=${prevCursor}`;
        } else if (!usePrevCursor && currentCursor) {
            url += `&cursor=${currentCursor}`;
        }
        
        if (currentFilters.search) url += `&search=${encodeURIComponent(currentFilters.search)}`;
        if (currentFilters.level) url += `&level=${encodeURIComponent(currentFilters.level)}`;
        if (currentFilters.rating) url += `&rating=${encodeURIComponent(currentFilters.rating)}`;
        if (currentFilters.event_type) url += `&event_type=${encodeURIComponent(currentFilters.event_type)}`;

        const response = await fetch(url, { headers: { 'X-Token': TOKEN } });
        const result = await response.json();

        if (result.status === 'success') {
            displayApplicants(result.data.applicants);
            
            // Update cursor state
            hasMore = result.data.has_more;
            if (!usePrevCursor) {
                currentCursor = result.data.next_cursor;
            }
            prevCursor = result.data.prev_cursor;
            
            updatePaginationButtons();
            
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
            <td>${escapeHtml(app.ushered_by || 'NA')}</td>
            <td>${escapeHtml(app.event_type || 'Interview')}</td>
            <td>${escapeHtml(app.interviewed_by || 'NA')}</td>
            <td>
                <button class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;" onclick="redirectEdit(${app.id})">âœï¸ Edit</button>
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
        if (response.status === 401) window.location.href = 'register.html';
        
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
function updatePaginationButtons() {
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const pageInfo = document.getElementById('page-info');
    
    // Update button states
    btnPrev.disabled = !prevCursor;
    btnNext.disabled = !hasMore;
    
    // Update info text
    pageInfo.innerText = hasMore ? 'More results available' : 'End of results';
}

function nextPage() {
    if (hasMore && currentCursor) {
        loadApplicants(false); // Load next page
    }
}

function previousPage() {
    if (prevCursor) {
        // Reset to beginning
        currentCursor = null;
        loadApplicants(false);
    }
}


// Clear Filters
function clearFilters() {
    currentFilters = { search: '', level: '', rating: '', event_type: '' };
    document.getElementById('search').value = '';
    document.getElementById('level-filter').value = '';
    document.getElementById('rating-filter').value = '';
    document.getElementById('event-type-filter').value = '';
    currentCursor = null; // Reset cursor
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



