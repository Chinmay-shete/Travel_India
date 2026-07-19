function init() {
    gsap.registerPlugin(ScrollTrigger);

    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    });

    lenis.on('scroll', ScrollTrigger.update);

    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
    });
    gsap.ticker.lagSmoothing(0);
}

init()

function bhai(params) {
    gsap.from(".page1 img",{
        scale:2,
        duration:2,
    })
    gsap.from(".heading h1",{
        opacity:1,
        duration:1,
        stagger:0.15,
        y:80,
    })
    gsap.from("h3 span",{
        y:50,
        duration:0.15,
        opacity:0,
        borderBottom:"1px solid black",
        stagger:0.15,
        scrollTrigger:{
            trigger:".page2",
            start:"top 0%",
            end:"top -100%",
            scrub:3,
            pin:true
        }
    })
    gsap.to(".page3 .photos", {
        transform: "translateX(-200%)",
        scrollTrigger: {
          trigger: ".page3 .photos",
          start: "top 0%",
          end: "top -200%",
          scrub: 1,
          pin: true,
        },
      });
        gsap.to(".marquee",{
        transform:"translateX(-100%)",
        duration:3,
        repeat:-1,
        ease:"none"
      })
    }
bhai()

gsap.from(".page8 span",{
    y:-50,
    duration:7,
    delay:0.5,
    opacity:0,
    stagger:2,
    scrollTrigger: {
        trigger: ".page7 .bottom-part3",
        start: "top 0%",
        end: "top 100%",
        scrub: 8,
      },
})

var menu = document.querySelector("#menu").addEventListener("click",()=>{
    var tl = gsap.timeline()
   tl.to("#menu",{
    opacity:0,
    duration:0.30,
    display:"none",
   })
tl.to(".menu-section",{
    top:0,
})
tl.from(".menu-part1 h3",{
    y:-50,
    duration:1,
    opacity:0,
    stagger:0.15,
})
})

document.querySelector(".main").addEventListener("wheel",()=>{
gsap.to(".menu-section",{
    top:"-11vh",
})
gsap.to("#menu",{
    opacity:1,
    display:"block",
})
})

var main = document.querySelector(".main");
var curser = document.querySelector(".curser");
main.addEventListener("mousemove", function (dets) {
    gsap.to(curser, {
      x: dets.x,
      y: dets.y,
      opacity: 1,
      duration: 0.8,
    });
  });

document.querySelector("#button").addEventListener("click",()=>{
    window.location.href="../booking/booking.html"
})
document.querySelector("#button1").addEventListener("click",()=>{
    window.location.href="../booking/booking.html"
})
document.querySelector("#home").addEventListener("click",()=>{
    window.location.href="../index.html"
})
document.querySelector("#logout").addEventListener("click",()=>{
    window.location.href="../config/logout.php"
})
