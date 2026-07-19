// landing page ==>
const navText = new SplitType(".nav-part1 h5");

const first = function () {
  let tl = gsap.timeline();
  tl.from(".page1 .nav", {
    y: "-100%",
    duration: 0.3,
  });
  const headings = new SplitType(".header h1", { types: "chars" });
  tl.from(headings.chars, {
    y: 90, // Start slightly below
    rotationX: 90, // Rotate on X-axis (flip effect)
    opacity: 0, // Start from invisible
    duration: 1, // Increase the duration for a slower flow
    stagger: {
      amount: 0.5, // Control how long the stagger lasts (makes the flow feel natural)
      from: "start", // Start the stagger from the first character
      // grid: "auto",     // Make the characters flow naturally
    },
    ease: Expo.easeInOut,
  });
  $(document).ready(function () {
    $(".backSide img").each(function () {
      $(this).delay(500).animate(
        {
          width: "100%",
        },
        2500
      );
    });
  });
  tl.to(".middle", {
    opacity: 1,
    duration: 2,
    ease: Expo.easeInOut,
  });
};
first();

const backImg = document.querySelectorAll(".backSide img");
backImg.forEach((img) => {
  addEventListener("mousemove", (e) => {
    const mouseX = e.clientX;
    const mouseY = e.clientY;
    const screenWidth = window.innerWidth;
    const screenHeight = window.innerHeight;

    const movementX = (screenWidth / 2 - mouseX) * 0.1;
    const movementY = (screenHeight / 2 - mouseY) * 0.1;

    gsap.to(img, {
      x: movementX,
      y: movementY,
      duration: 6,
      ease: "slow(0.1,0.1,false)",
    });
  });
});

const locationText = new SplitType(".location h1", { types: "chars" });
const animation = gsap.from(locationText.chars, {
  y: 90,
  rotationX: 90,
  duration: 0.8,
  stagger: {
    amount: 3,
    from: "start",
  },
opacity:1,
  ease: "power2.out",
  paused: true,
});
ScrollTrigger.create({
  trigger: ".page3",
  scroller: "body", 
  start: "top 0%",
  end: "top -100%", 
  onEnter: () => {
    animation.restart();
    gsap.set(".location h1",{opacity:1})
    gsap.set(".pageImg", { display: "none" });
  },
  onLeaveBack: () => {
    gsap.set(".location h1",{opacity:0})
    // animation.restart(); // Restart the animation when scrolling back
    gsap.set(".pageImg", { display: "none" }); // Show .pageImg again
  },
});

//landing page end ==>

//menu close    ==>
document.querySelector(".close").addEventListener("click", () => {
  gsap.to(".page1-part1", {
    top: "-105vh",
    duration: 1.05,
    overflow: "hidden",
    ease: "elastic.out(0.5,1)",
  });
});

//menu start==>
document.querySelector(".open").addEventListener("click", () => {
  gsap.to(".page1-part1", {
    top: 0,
    overflow: "hidden",
    duration: 1.05,
    ease: "elastic.out(0.5,1)",
  });

  const headings = new SplitType(".animate-text");
  gsap.to(".char", {
    y: 0,
    duration: 1,
    delay: 0.2,
    opacity: 1,
  });
});
// menu end ==>


// menu under section ==>
const headings = document.querySelectorAll(".menu h1");
const images = document.querySelectorAll(".images img");
document.querySelectorAll(".menu h1").forEach((heading, index) => {
  heading.addEventListener("mouseenter", () => {
    gsap.to(".menu h1", {
      opacity: 0.5,
      duration: 0.3,
      ease: "sine.out",
    });

    gsap.to(heading, {
      opacity: 1,
      duration: 0.3,
      ease: "sine.out",
    });
  });

  heading.addEventListener("mouseleave", () => {
    gsap.to(".menu h1", {
      opacity: 1,
      duration: 0.3,
    });
  });
}); 

function hideAllImages() {
  images.forEach((img) => img.classList.remove("active"));
}
headings.forEach((heading) => {
  heading.addEventListener("mouseenter", () => {
    const index = heading.getAttribute("data-index") - 1;
    hideAllImages();
    if (images[index]) {
      images[index].classList.add("active");
    }
  });

  heading.addEventListener(
    "mouseleave",
    () => {
      gsap.to(images, {
        duration: 2,
        delay: 2,
      });
    },
    hideAllImages
  );
});
hideAllImages();
//  menu under end==>

// login start==>
const signIn = document.querySelector(".signIn");
const signUpPageH = new SplitType(".signUpPage-bottom h1", { types: "lines" });
signIn.addEventListener("click", () => {
  let tp = gsap.timeline();
  tp.to(".signInPage", {
    left: 0,
    ease: "elastic.out(0.5,1)",
    duration: 0.5,
    stagger:0.5
  });
 tp.from(".signInPage .nav",{
  y:"-100%",
  // color:"red",
  duration:0.5,
  stagger:0.9
 })
 tp.from(".animated-hr",{
  width:"0%",
  duration:1
 })
 tp.from(".signUpPage-part11 h3",{
  y:100, 
  duration:0.5,
  opacity:0
 })  
 tp.from(signUpPageH.lines,{
  y: "100%",
  stagger:0.09,
    duration: 1,
    opacity:0,
    // color:"red"
 })
 gsap.from(".container form", {
  y: "100%",
  delay:1,
  stagger: 2,
  duration:1.5,
  opacity: 0,
  // backgroundColor: "blue",
  ease: "expo.inOut",
});

//  tp.from(".container input",{
//   y:"100%",
//   stagger:0.09,
//   opacity:0, 
//   duration:0.09,
//   ease: Expo.easeInOut,
//  })
//  tp.from(".container button",{
//   y:"100%",
//   stagger:0.09,
//   opacity:0,
//   duration:0.02,
//   ease: Expo.easeInOut,
//  })
 
});
const closeSignIn = document.querySelector(".closeSignIn");
closeSignIn.addEventListener("click", () => {
  gsap.to(".signInPage", {
    left: "-100%",
    ease: "elastic.out(0.5,1)",
  });
});

// logn end ==>

//signup start==>
const signUp = document.querySelector(".signUp");
signUp.addEventListener("click", () => {
  let tt = gsap.timeline();
  tt.to(".signUpPage", {
    left: 0,
    ease: "elastic.out(0.5,1)",
    duration: 0.01,
    stagger:0.5
  });
 tt.from(".signUpPage .nav",{
  y:"-100%", 
  duration:0.5,
  stagger:0.9
 })
 tt.from(".animated-hr",{
  width:"0%",
  duration:0.5
 })
 tt.from(".signUpPage-part11 h3",{
  y:100, 
  duration:0.5,
  opacity:0
 })  
 tt.from(signUpPageH.lines,{
  y: "100%",
  stagger:0.09,
    duration: 1,
    opacity:0,
    // color:"red"
 })
 gsap.from(".container form", {
  y: "100%",
  delay:1,
  stagger: 2,
  duration:1.5,
  opacity: 0,
  // backgroundColor: "blue",
  ease: "expo.inOut",
});
});
const closeSignUp = document.querySelector(".signUpPage .closeSignUp");
closeSignUp.addEventListener("click", () => {
  gsap.to(".signUpPage", {
    left: "-100%",
    ease: "elastic.out(0.5,1)",
  });
});
// signup end ==>

const text = new SplitType(".text h6", { types: "lines" });

//  const headings = new SplitType(".header h1", { types: "chars" });
const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
});

function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

const tl = gsap
  .timeline({
    scrollTrigger: {
      trigger: ".img",
      scrub: true,
    },
  })
  .to(".img", {
    stagger: 0.9,
    y: -700,
    scrub: true,
    duration: 5,
    ease: "slow(0.7,0.7,false)",
  });
var tp = gsap.timeline({
  scrollTrigger: {
    trigger: "#page6",
    // markers:true,
    start: "38% 50%",
    end: "100% 50%",
    scrub: 2,
    pin: true,
  },
});
tp.to(
  ".text",
  {
    top: "-7%",
  },
  "a"
)
  .to(
    "#card-one",
    {
      top: "35%",
    },
    "a"
  )
  .to(
    "#card-two",
    {
      top: "130%",
    },
    "a"
  )
  .to(
    "#card-two",
    {
      top: "42%",
    },
    "b"
  )
  .to(
    "#card-one",
    {
      width: "65%",
      height: "65vh",
    },
    "b"
  )
  .to(
    "#card-three",
    {
      top: "130%",
    },
    "b"
  )
  .to(
    "#card-three",
    {
      top: "50%",
    },
    "c"
  )
  .to(
    "#card-two",
    {
      width: "70%",
      height: "70vh",
    },
    "c"
  );

  document.querySelector(".header button").addEventListener("mouseenter",()=>{
    console.log("hiii")
    gsap.to(".header a ",{
      color:"white"
    })
  })
 