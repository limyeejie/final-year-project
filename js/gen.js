// Hero Section
const canvas = document.getElementById('networkCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const nodes = [];
const colors = ['#00FF00', '#00FFFF', '#FF00FF', '#FFFFFF', '#FF0000']; // Neon Green, Cyan, Magenta, White, Red

// Increase the number of nodes
for (let i = 0; i < 80; i++) {  // Increase from 30 to 50 nodes
    nodes.push(createNode(Math.random() * canvas.width, Math.random() * canvas.height));
}

function createNode(x, y) {
    return {
        x: x,
        y: y,
        radius: 4 + Math.random() * 4,  // Slightly smaller, randomized size
        color: colors[Math.floor(Math.random() * colors.length)],
        moveX: (Math.random() - 0.5) * 2,
        moveY: (Math.random() - 0.5) * 2
    };
}

// Create gradient for line connections
function createGradient(x1, y1, x2, y2) {
    const grad = ctx.createLinearGradient(x1, y1, x2, y2);
    grad.addColorStop(0, '#00FF00');  // Neon Green
    grad.addColorStop(0.5, '#00FFFF');  // Cyan
    grad.addColorStop(1, '#FF00FF');  // Neon Magenta
    return grad;
}

// Draw Lines between nodes with neon gradient effect
function drawLines() {
    nodes.forEach((node1, index1) => {
        nodes.forEach((node2, index2) => {
            if (index1 !== index2) {
                let dist = Math.sqrt((node1.x - node2.x) ** 2 + (node1.y - node2.y) ** 2);
                if (dist < 100) { // Reduced distance threshold for more connections
                    const grad = createGradient(node1.x, node1.y, node2.x, node2.y);
                    ctx.strokeStyle = grad;
                    ctx.lineWidth = 1.5;  // Thinner lines for added detail
                    ctx.beginPath();
                    ctx.moveTo(node1.x, node1.y);
                    ctx.lineTo(node2.x, node2.y);
                    ctx.stroke();
                }
            }
        });
    });
}

// Animate Nodes and Lines with neon effect
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    nodes.forEach((node) => {
        node.x += node.moveX;
        node.y += node.moveY;

        // Boundary check
        if (node.x < 0 || node.x > canvas.width) node.moveX = -node.moveX;
        if (node.y < 0 || node.y > canvas.height) node.moveY = -node.moveY;

        // Create a radial gradient to simulate 3D depth and neon glow
        const gradient = ctx.createRadialGradient(node.x, node.y, 0, node.x, node.y, node.radius * 2);
        gradient.addColorStop(0, '#ffffff');  // White center for the glow effect
        gradient.addColorStop(1, node.color); // Neon color on the outer edge

        ctx.fillStyle = gradient;
        ctx.beginPath();
        ctx.arc(node.x, node.y, node.radius, 0, Math.PI * 2);
        ctx.fill();
    });

    drawLines();
    requestAnimationFrame(animate);
}

animate();

// JavaScript to trigger animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const textContainer = document.querySelector('.text-container');
    const textItems = document.querySelectorAll('.text-item');
    const section = document.querySelector('.text-animation-section');

    // Function to check if the section is in view
    function checkVisibility() {
        const sectionTop = section.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (sectionTop <= windowHeight * 0.75) {
            textContainer.classList.add('show');
            textItems.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                }, index * 300); // Delay each text item
            });
        }
    }

    // Trigger the checkVisibility function on scroll and load
    window.addEventListener('scroll', checkVisibility);
    checkVisibility(); // Trigger on load in case the section is already in view
});

//Images
const getTanFromDegrees = (deg) => Math.tan((deg * Math.PI) / 180);
const camera = document.querySelector(".camera");
const cameraWidth = camera.offsetWidth;
const swiper = document.querySelector(".swiper");
const swiperWidth = swiper.offsetWidth;
const dCarousel = document.querySelector(".d-carousel");
const dCarouselItems = dCarousel.children;
const dCarouselItemCount = dCarouselItems.length;
const dCarouselItemDeg = 360 / dCarouselItemCount;
const dCarouselItemTanDegHalf = Math.tan(getTanFromDegrees(dCarouselItemDeg / 2));
const dCarouselItemR = cameraWidth / 2 / dCarouselItemTanDegHalf;
let swipeDeg = 0;
let previousDeltaX = 0;
let deltaXDelta = 0;

dCarousel.style.setProperty("--d-carousel-item-r", `${dCarouselItemR}px`);
dCarousel.style.setProperty("--d-carousel-item-deg", `${dCarouselItemDeg}deg`);

Array.from(dCarouselItems).forEach((item, i) => {
  item.style.setProperty("--i", `${i}`);
});

const manager = new Hammer.Manager(swiper);
const Pan = new Hammer.Pan({ threshold: 10 });
manager.add(Pan);

manager.on("pan", (e) => {
  const deltaX = e.deltaX;
  deltaXDelta = deltaX - previousDeltaX;
  previousDeltaX = deltaX;

  const direction = e.offsetDirection;
  if (direction === 2 || direction === 4) { // left or right pan
    const swipeDegDelta = (deltaXDelta / swiperWidth) * 360;
    swipeDeg -= swipeDegDelta;

    // Calculate the maximum allowable translation to align the last image's right edge
    const maxTranslation = dCarouselItemCount * (230 + 75) - swiperWidth; // Adjust to match your image width + margin
    if (swipeDeg < 0) {
      swipeDeg = 0; // Prevent moving before the 1st image
    } else if (swipeDeg > maxTranslation) {
      swipeDeg = maxTranslation; // Prevent moving beyond the last image
    }

    dCarousel.style.transform = `translateX(-${swipeDeg}px)`;
  }

  if (e.isFinal) {
    previousDeltaX = 0;
  }
});

const disableTouchMove = () => {
  document.body.addEventListener(
    "touchmove",
    (e) => {
      e.preventDefault();
    },
    { passive: false }
  );
};
disableTouchMove();