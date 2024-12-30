let canSpin = true;
let countdownInterval;

function spinWheel() {
    if (!canSpin) {
        alert("You can spin only once per 10 seconds. Please wait.");
        return;
    }

    canSpin = false;
    const wheel = document.getElementById('wheel');
    const randomDegree = Math.floor(Math.random() * 360);
    const rotation = 360 * 5 + randomDegree; // 5 full rotations + random angle

    wheel.style.transition = 'transform 3s ease-out';
    wheel.style.transform = `rotate(${rotation}deg)`;

    setTimeout(() => {
        const segment = Math.floor((360 - (rotation % 360)) / 30) + 1;
        displayRewardModal(segment);
        
        // Start countdown
        startCountdown(10);
    }, 4000); // Wait for the spin animation to complete
}

function startCountdown(duration) {
    let timer = duration;
    const countdownElement = document.getElementById('countdown');
    countdownElement.textContent = `You can spin again in ${timer} seconds.`;

    countdownInterval = setInterval(() => {
        timer--;
        countdownElement.textContent = `You can spin again in ${timer} seconds.`;

        if (timer <= 0) {
            clearInterval(countdownInterval);
            canSpin = true;
            countdownElement.textContent = '';
        }
    }, 1000);
}

function displayRewardModal(segment) {
    const modal = document.getElementById('rewardModal');
    const messageElement = document.getElementById('rewardMessage');
    const pdfButton = document.getElementById('openPdfButton');
    modal.style.display = 'flex';

    const prizes = [
        "20% discount on offline book purchase",
        "40% discount on offline book purchase",
        "60% discount on offline book purchase",
        "Free online Book PDF ",
        "Free online Book PDF ",
        "Sorry, try again",
        "Sorry, try again",
        "Free online Book PDF ",
        "Sorry, try again",
        "Free online Book PDF ",
        "Sorry, try again",
        "Free online Book PDF ",
        "Sorry, try again"
    ];

    const downloadLinks = {
        4: "https://drive.google.com/file/d/13dOFlZhLuq7hNgnC6c_ap4q-wZKdgGtF/view?usp=drive_link",
        5: "https://drive.google.com/file/d/13dOFlZhLuq7hNgnC6c_ap4q-wZKdgGtF/view?usp=drive_link",
        8: "https://drive.google.com/file/d/13dOFlZhLuq7hNgnC6c_ap4q-wZKdgGtF/view?usp=drive_link",
        10: "https://drive.google.com/file/d/13dOFlZhLuq7hNgnC6c_ap4q-wZKdgGtF/view?usp=drive_link",
        12: "https://drive.google.com/file/d/13dOFlZhLuq7hNgnC6c_ap4q-wZKdgGtF/view?usp=drive_link"
    };

    const couponCodes = {
        1: "20OFFBOOKS",
        2: "40OFFBOOKS",
        3: "60OFFBOOKS"
    };

    if (downloadLinks[segment]) {
        messageElement.textContent = `Congratulations! You won: ${prizes[segment - 1]}.`;
        pdfButton.style.display = 'block';
        pdfButton.onclick = () => {
            window.open(downloadLinks[segment], '_blank');
        };
    } else if (couponCodes[segment]) {
        messageElement.textContent = `Congratulations! You won: ${prizes[segment - 1]}. Your coupon code is: ${couponCodes[segment]}`;
        pdfButton.style.display = 'none';
    } else {
        messageElement.textContent = ` ${prizes[segment - 1]}`;
        pdfButton.style.display = 'none';
    }
}

function closeModal() {
    const modal = document.getElementById('rewardModal');
    modal.style.display = 'none';
}
