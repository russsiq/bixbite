<template></template>

<script>
    var ScrollTop = document.createElement('div');
    
    export default {
        mounted() {
            ScrollTop.className = 'scrolltop';
            ScrollTop.innerHTML = '▲';
            ScrollTop.addEventListener('click', this.clickScroll);
            ScrollTop.addEventListener('mousemove', this.hoverScroll);
            document.body.appendChild(ScrollTop);
            
            window.addEventListener('scroll', this.handleScroll);
            
            console.log('Component `ScrollTop` mounted.');
        },
        methods: {
            handleScroll () {
                ScrollTop.style.opacity = window.scrollY > 160 ? .5 : 0;
            },
            clickScroll () {
                window.scrollTo(0, 0);
            },
            hoverScroll () {
                ScrollTop.style.opacity = 1;
            }
         }
    }
</script>

<style>
    .scrolltop {
        cursor: pointer;
        opacity: 0;
        position: fixed;
        z-index: 999;
        bottom: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 4px;
        text-align: center;
        font-size: 24px;
        background: #000;
        color: #fff;
        transition: all .3s ease-in-out;
    }
</style>