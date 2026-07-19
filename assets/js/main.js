const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
});

lenis.on('scroll', ScrollTrigger.update);

gsap.ticker.add((time)=>{
  lenis.raf(time * 1000)
});

gsap.ticker.lagSmoothing(0);

document.querySelector(".close").addEventListener("click", () => {
  gsap.to(".page1-part1", {
    top: "-105vh",
    duration: 1.05,
    overflow: "hidden",
  });
});
document.querySelector(".open").addEventListener("click", () => {
  gsap.to(images, {
    opacity: 1,
  });

  gsap.to(".page1-part1", {
    top: 0,
    overflow: "hidden",
    duration: 1.2,
    ease: "elastic.out(0.5, 1)",
  });
  const headings = new SplitType(".animate-text");

  gsap.to(".char", {
    y: 0,
    stagger: 0.05,
    duration: 1.2,
    delay: 0.5,
    opacity: 1,
  });
});

document.querySelectorAll(".menu h1").forEach((heading, index) => {
  heading.addEventListener("mouseenter", () => {
    gsap.to(".menu h1", {
      opacity: 0.5,
      duration: 0.3,
    });

    gsap.to(heading, {
      opacity: 1,
      duration: 0.3,
    });
  });

  heading.addEventListener("mouseleave", () => {
    gsap.to(".menu h1", {
      opacity: 1,
      duration: 0.3,
    });
  });
});

const headings = document.querySelectorAll(".menu h1");
const images = document.querySelectorAll(".images img");

// Hide all images
function hideAllImages() {
  images.forEach((img) => img.classList.remove("active"));
}

// Add event listeners to headings
headings.forEach((heading) => {
  heading.addEventListener("mouseenter", () => {
    const index = heading.getAttribute("data-index") - 1; // Convert to zero-based index
    // Hide all images
    hideAllImages();

    // Show the corresponding image
    if (images[index]) {
      images[index].classList.add("active");
    }
  });

  heading.addEventListener("mouseleave", () => {
    hideAllImages();
    gsap.to(headings, { opacity: 1, scale: 1, duration: 0.3 });
  });
});

// Initially hide all images
hideAllImages();

// Sign In / Sign Up side panels functionality
const signIn = document.querySelector(".signIn");
if (signIn) {
  signIn.addEventListener("click", () => {
    gsap.to(".login", {
      right: 0,
      duration: 1,
    });
  });
}
const closeSignIn = document.querySelector(".closeSide");
if (closeSignIn) {
  closeSignIn.addEventListener("click", () => {
    gsap.to(".login", {
      right: "110%",
    });
  });
}

const signUp = document.querySelector(".sign");
if (signUp) {
  signUp.addEventListener("click", () => {
    gsap.to(".signin", {
      right: 0,
      duration: 1,
    });
  });
}
const closeSignUp = document.querySelector(".signin-part1 .closeSide");
if (closeSignUp) {
  closeSignUp.addEventListener("click", () => {
    gsap.to(".signin", {
      right: "110%",
    });
  });
}