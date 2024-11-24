// Fungsi untuk menampilkan detail UKM yang dipilih
function showUkmDetails(ukmId) {
    const allContents = document.querySelectorAll('.ukm-content');
    const selectedContent = document.getElementById(ukmId);
    
    // Sembunyikan semua konten UKM
    allContents.forEach(content => content.style.display = 'none');

    // Tampilkan konten UKM yang dipilih
    selectedContent.style.display = 'block';

    // Tampilkan section detail UKM
    document.querySelector('.ukm-details').style.display = 'block';
}
