document.addEventListener('mousemove', function(e) {
    let body = document.querySelector('body');
    let circle = document.createElement('span');
    let x = e.clientX; // 🔹 클라이언트 좌표 사용
    let y = e.clientY;
    circle.style.left = x + "px";
    circle.style.top = y + "px";
    let size = Math.random() * 100;
    circle.style.width = 20 + size + "px";
    circle.style.height = 20 + size + "px";
    body.appendChild(circle);
    setTimeout(function() {
        circle.remove();
    }, 1800);
});
