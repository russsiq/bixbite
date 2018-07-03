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
