function init() {
    gsap.registerPlugin(ScrollTrigger);

    const locoScroll = new LocomotiveScroll({
        el: document.querySelector(".main"),
        smooth: true
    });
    locoScroll.on("scroll", ScrollTrigger.update);

    ScrollTrigger.scrollerProxy(".main", {
        scrollTop(value) {
            return arguments.length ? locoScroll.scrollTo(value, 0, 0) : locoScroll.scroll.instance.scroll.y;
        }, 
        getBoundingClientRect() {
            return { top: 0, left: 0, width: window.innerWidth, height: window.innerHeight };
        },
        pinType: document.querySelector(".main").style.transform ? "transform" : "fixed"
    });


    ScrollTrigger.addEventListener("refresh", () => locoScroll.update());

    ScrollTrigger.refresh();

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
        // delay:1,
        duration:0.15,
        opacity:0,
        borderBottom:"1px solid black",
        stagger:0.15,
        scrollTrigger:{
            trigger:".page2",
            scroller:".main",
            // markers:true,
            start:"top 0%",
            end:"top -100%",
            scrub:3,
            pin:true
        }
    })
    gsap.to(".page3 .photos", {
        transform: "translateX(-200%)",
        // opacity:0,
        scrollTrigger: {
          trigger: ".page3 .photos",
          scroller: ".main",
          // markers:true,
          start: "top 0%",
          end: "top -200%",
          scrub: 1,
          pin: true,
        },
      });
        gsap.to(".marquee",{
        transform:"translateX(-100%)",
        // delay:1,
        duration:3,
        repeat:-1,
        ease:"none"
      })
    }
bhai()
gsap.from(".page8 span",{
    y:-50,
    // delay:1,
    duration:7,
    delay:0.5,
    opacity:0,
    stagger:2,
    scrollTrigger: {
        trigger: ".page7 .bottom-part3",
        scroller: ".main",
        // markers:true,
        start: "top 0%",
        end: "top 100%",
        scrub: 8,
        // pin: true,
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
    // duration:0.15
})
tl.from(".menu-part1 h3",{
    y:-50,
    // delay:1,
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


