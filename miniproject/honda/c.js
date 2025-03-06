// เรียกใช้ปุ่มที่เป็นภาพที่สามารถคลิกได้
const images = document.querySelectorAll('.clickable-image');

// เรียกใช้ lightbox และ lightbox image
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');

// เรียกปุ่มปิด
const closeBtn = document.getElementById('close');

// เมื่อคลิกที่ภาพใน collage จะเปิด lightbox ขึ้นมา
images.forEach(image => {
    image.addEventListener('click', (event) => {
        lightbox.style.display = 'flex';  // แสดง lightbox
        lightboxImg.src = event.target.src;  // ตั้งค่ารูปภาพที่แสดงใน lightbox
    });
});

// เมื่อคลิกปุ่มปิด (close) จะปิด lightbox
closeBtn.addEventListener('click', () => {
    lightbox.style.display = 'none';  // ซ่อน lightbox
});
