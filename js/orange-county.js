const locoScroll = new LocomotiveScroll({
    el: document.querySelector(".main"),
    smooth: true,
  });
  document.querySelector(".close").addEventListener("click", () => {
    gsap.to(".page1-part1", {
      top: "-105vh",
      duration: 1.05,
      overflow: "hidden",
    });
  });
  document.querySelector(".open").addEventListener("click", () => {
    gsap.to(images,{
     opacity:1,   
  //  duration:2,
  //  delay:2
  })
   
    gsap.to(".page1-part1", {
      top: 0,
      overflow: "hidden",
      duration: 1.05,
    });
    const headings = new SplitType(".animate-text");
  
    gsap.to(".char", {
      y: 0,
      // stagger: 0.05,
      duration: 1.2,
      delay: 0.5,
      opacity: 1,
    });
  });
  
  document.querySelectorAll(".menu h1").forEach((heading,index) => {
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
  //  gsap.to(index,{
  //   opacity:1,
  //   duration:20
  //  })
      // Hide all images
      hideAllImages();
  
      // Show the corresponding image
      if (images[index]) {
        images[index].classList.add("active");
      }
    });
  
    heading.addEventListener("mouseleave",()=>{
           gsap.to(images,{
            
            duration:2,
            delay:2
           })
    } ,hideAllImages);
  });
  
  // Initially hide all images
  hideAllImages();
  
  
  
  