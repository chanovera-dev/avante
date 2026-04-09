// /**
//  * Signpost Animation for Crisis Academy
//  * Draws an animated signpost on Canvas with "CRISIS" and "OPPORTUNITY" signs.
//  */

// document.addEventListener('DOMContentLoaded', function () {
//     const canvas = document.getElementById('signpostCanvas');
//     if (!canvas) return;

//     const ctx = canvas.getContext('2d');
//     const container = canvas.parentElement;

//     let width, height;
//     let angle = 0;

//     function resize() {
//         width = container.clientWidth;
//         height = container.clientHeight || 400;
//         canvas.width = width * window.devicePixelRatio;
//         canvas.height = height * window.devicePixelRatio;
//         canvas.style.width = width + 'px';
//         canvas.style.height = height + 'px';
//         ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
//         requestAnimationFrame(render);
//     }

//     window.addEventListener('resize', resize);
//     resize();

//     // Signpost properties
//     const poleWidth = 18;
//     const signWidth = 240;
//     const signHeight = 70;

//     function drawSign(x, y, text, color, direction, sway, tilt) {
//         ctx.save();
//         ctx.translate(x, y);

//         // Perspective tilt effect
//         const perspective = direction === 'left' ? -tilt : tilt;
//         ctx.transform(1, perspective * 0.1, 0, 1, 0, 0);
//         ctx.rotate(sway * 0.04);

//         // Draw shadow
//         ctx.shadowColor = 'rgba(0,0,0,0.4)';
//         ctx.shadowBlur = 12;
//         ctx.shadowOffsetY = 8;
//         ctx.shadowOffsetX = direction === 'left' ? -4 : 4;

//         const width = signWidth;
//         const height = signHeight;
//         const arrowHead = height * 0.8; // Width of the triangular part

//         // --- Outer Border (White Frame) ---
//         ctx.beginPath();
//         if (direction === 'left') {
//             ctx.moveTo(0, -height / 2); // Flat edge at center
//             ctx.lineTo(-width + arrowHead, -height / 2); // Top left (before point)
//             ctx.lineTo(-width - 20, 0); // Point left, extended
//             ctx.lineTo(-width + arrowHead, height / 2); // Bottom left
//             ctx.lineTo(0, height / 2); // Flat edge at center
//         } else {
//             ctx.moveTo(0, -height / 2); // Flat edge at center
//             ctx.lineTo(width - arrowHead, -height / 2); // Top right (before point)
//             ctx.lineTo(width + 20, 0); // Point right, extended
//             ctx.lineTo(width - arrowHead, height / 2); // Bottom right
//             ctx.lineTo(0, height / 2); // Flat edge at center
//         }
//         ctx.closePath();

//         ctx.fillStyle = '#FFFFFF';
//         ctx.fill();

//         // --- Inner Colored Area ---
//         const bWidth = 5; // Border width
//         ctx.beginPath();
//         if (direction === 'left') {
//             ctx.moveTo(-bWidth, -height / 2 + bWidth);
//             ctx.lineTo(-width + arrowHead + 2, -height / 2 + bWidth);
//             ctx.lineTo(-width - 20 + bWidth * 2.5, 0);
//             ctx.lineTo(-width + arrowHead + 2, height / 2 - bWidth);
//             ctx.lineTo(-bWidth, height / 2 - bWidth);
//         } else {
//             ctx.moveTo(bWidth, -height / 2 + bWidth);
//             ctx.lineTo(width - arrowHead - 2, -height / 2 + bWidth);
//             ctx.lineTo(width + 20 - bWidth * 2.5, 0);
//             ctx.lineTo(width - arrowHead - 2, height / 2 - bWidth);
//             ctx.lineTo(bWidth, height / 2 - bWidth);
//         }
//         ctx.closePath();

//         // Gradient for sign background
//         const signGrad = ctx.createLinearGradient(0, -height / 2, 0, height / 2);
//         signGrad.addColorStop(0, adjustColor(color, 15)); // Lighter top (highlight)
//         signGrad.addColorStop(0.3, color);
//         signGrad.addColorStop(1, adjustColor(color, -25)); // Darker bottom
//         ctx.fillStyle = signGrad;
//         ctx.fill();

//         // Subtle highlight line inside the color area (embossing effect)
//         ctx.strokeStyle = 'rgba(255, 255, 255, 0.6)';
//         ctx.lineWidth = 1;
//         ctx.stroke();

//         // Inner thin line mimicking reflective border
//         const innerGap = 5;
//         ctx.beginPath();
//         if (direction === 'left') {
//             ctx.moveTo(-bWidth - innerGap, -height / 2 + bWidth + innerGap);
//             ctx.lineTo(-width + arrowHead + 5, -height / 2 + bWidth + innerGap);
//             ctx.lineTo(-width - 20 + bWidth * 2.5 + innerGap * 1.8, 0);
//             ctx.lineTo(-width + arrowHead + 5, height / 2 - bWidth - innerGap);
//             ctx.lineTo(-bWidth - innerGap, height / 2 - bWidth - innerGap);
//         } else {
//             ctx.moveTo(bWidth + innerGap, -height / 2 + bWidth + innerGap);
//             ctx.lineTo(width - arrowHead - 5, -height / 2 + bWidth + innerGap);
//             ctx.lineTo(width + 20 - bWidth * 2.5 - innerGap * 1.8, 0);
//             ctx.lineTo(width - arrowHead - 5, height / 2 - bWidth - innerGap);
//             ctx.lineTo(bWidth + innerGap, height / 2 - bWidth - innerGap);
//         }
//         ctx.closePath();
//         ctx.strokeStyle = '#FFFFFF';
//         ctx.lineWidth = 3;
//         ctx.stroke();

//         // --- Text ---
//         ctx.shadowColor = 'rgba(0,0,0,0.4)';
//         ctx.shadowBlur = 3;
//         ctx.shadowOffsetY = 2;
//         ctx.shadowOffsetX = 2;
//         ctx.fillStyle = '#FFFFFF';

//         // Font setup mimicking image
//         ctx.font = '800 24px "Arial Narrow", "Helvetica Condensed", "Impact", sans-serif';
//         ctx.textAlign = 'center';
//         ctx.textBaseline = 'middle';

//         // Calculate text position
//         let textOffsetX = direction === 'left' ? (-width / 2) + 15 : (width / 2) - 15;
//         if (text === 'OPORTUNIDAD') { textOffsetX -= 8; } // Visual optical adjustment for green sign
//         if (text === 'CRISIS') { textOffsetX += 5; }

//         // Transform for text to counteract perspective slightly and look flat/painted
//         ctx.scale(0.9, 1.4);
//         ctx.fillText(text, textOffsetX * (1 / 0.9), 1);
//         ctx.scale(1 / 0.9, 1 / 1.4); // reset

//         ctx.restore();
//     }

//     function adjustColor(color, amount) {
//         let usePound = false;
//         if (color[0] == "#") {
//             color = color.slice(1);
//             usePound = true;
//         }
//         let num = parseInt(color, 16);
//         let r = (num >> 16) + amount;
//         if (r > 255) r = 255; else if (r < 0) r = 0;
//         let b = ((num >> 8) & 0x00FF) + amount;
//         if (b > 255) b = 255; else if (b < 0) b = 0;
//         let g = (num & 0x0000FF) + amount;
//         if (g > 255) g = 255; else if (g < 0) g = 0;
//         return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16).padStart(6, '0');
//     }

//     function render() {
//         ctx.clearRect(0, 0, width, height);

//         const centerX = width / 2;
//         const baseY = height - 20;
//         const poleHeight = height * 0.85;
//         const topY = baseY - poleHeight;

//         // Draw Pole
//         const gradient = ctx.createLinearGradient(centerX - poleWidth / 2, 0, centerX + poleWidth / 2, 0);
//         gradient.addColorStop(0, '#555');
//         gradient.addColorStop(0.2, '#999');
//         gradient.addColorStop(0.4, '#eee');
//         gradient.addColorStop(0.5, '#fff'); // Main highlight
//         gradient.addColorStop(0.7, '#bbb');
//         gradient.addColorStop(1, '#666');

//         ctx.shadowColor = 'rgba(0,0,0,0.5)';
//         ctx.shadowBlur = 10;
//         ctx.shadowOffsetX = 5;
//         ctx.shadowOffsetY = 5;

//         ctx.fillStyle = gradient;
//         ctx.fillRect(centerX - poleWidth / 2, topY, poleWidth, poleHeight);

//         // Reset shadow for details
//         ctx.shadowColor = 'transparent';

//         // Pole Cap
//         ctx.beginPath();
//         ctx.arc(centerX, topY, poleWidth * 0.9, 0, Math.PI * 2);

//         const capGrad = ctx.createRadialGradient(centerX - 2, topY - 2, 0, centerX, topY, poleWidth);
//         capGrad.addColorStop(0, '#fff');
//         capGrad.addColorStop(1, '#888');
//         ctx.fillStyle = capGrad;

//         ctx.fill();
//         ctx.strokeStyle = '#666';
//         ctx.lineWidth = 1;
//         ctx.stroke();

//         // Pole rings (connectors)
//         ctx.fillStyle = gradient;
//         ctx.strokeStyle = '#555';
//         ctx.lineWidth = 1;

//         // Top ring
//         const ringTopY = topY + poleHeight * 0.15 - 12;
//         ctx.fillRect(centerX - poleWidth * 1.2, ringTopY, poleWidth * 2.4, 24);
//         ctx.strokeRect(centerX - poleWidth * 1.2, ringTopY, poleWidth * 2.4, 24);

//         // Bottom ring
//         const ringBottomY = topY + poleHeight * 0.15 + signHeight;
//         ctx.fillRect(centerX - poleWidth * 1.2, ringBottomY, poleWidth * 2.4, 24);
//         ctx.strokeRect(centerX - poleWidth * 1.2, ringBottomY, poleWidth * 2.4, 24);

//         // Static parameters (no wind)
//         const sway1 = 0;
//         const sway2 = 0;
//         const tilt = 0;

//         // Draw signs
//         // Crisis (Red, Left) - Lower, drawn first so green can overlap if needed
//         // Red color from image: vibrant red #FF1414
//         drawSign(centerX, topY + poleHeight * 0.15 + signHeight + 8, 'CRISIS', '#D62727', 'left', sway2, -tilt);

//         // Opportunity (Green, Right) - Higher, drawn second
//         // Green color from image: bright green #14C814
//         drawSign(centerX, topY + poleHeight * 0.15 + 4, 'OPORTUNIDAD', '#28B428', 'right', sway1, tilt);
//     }

//     // render() is now called via requestAnimationFrame in resize()
// });