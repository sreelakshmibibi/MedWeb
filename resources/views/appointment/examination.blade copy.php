<style>
    .img-body {
        display: flex;
        justify-content: center;
        align-items: center;
        /* height: 100vh; */
        background-color: #f0f0f0;
    }

    .sqaure-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        background-color: #fff;
        border: 2px solid #ccc;
    }

    /* .triangle {
        width: 0;
        height: 0;
        position: absolute;
    } */

    .top-left {
        border-left: 100px solid transparent;
        border-bottom: 100px solid red;
        /* Triangle color */
        top: 0;
        left: 0;
    }

    .top-right {
        border-right: 100px solid transparent;
        border-bottom: 100px solid blue;
        /* Triangle color */
        top: 0;
        right: 0;
    }

    .bottom-left {
        border-left: 100px solid transparent;
        border-top: 100px solid green;
        /* Triangle color */
        bottom: 0;
        left: 0;
    }

    .bottom-right {
        border-right: 100px solid transparent;
        border-top: 100px solid yellow;
        /* Triangle color */
        bottom: 0;
        right: 0;
    }

    .dparts-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        background-color: #fff;
        border: 2px solid #ccc;
        display: flex;
    }

    .dparts {
        position: absolute;
        outline: 5px solid red;
    }

    .part-left {
        clip-path: polygon(0 0, 0 100%, 35% 50%);
        background-color: red;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .part-top {
        clip-path: polygon(0 0, 100% 0, 65% 50%, 35% 50%);
        background-color: blue;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .part-right {
        clip-path: polygon(100% 0, 100% 100%, 65% 50%);
        background-color: yellow;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .part-bottom {
        clip-path: polygon(35% 50%, 65% 50%, 100% 100%, 0 100%);
        background-color: yellowgreen;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .dpart-left {
        clip-path: polygon(0 0, 35% 35%, 35% 65%, 0 100%);
        background-color: red;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .dpart-top {
        clip-path: polygon(0 0, 100% 0, 65% 35%, 35% 35%);
        background-color: blue;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .dpart-right {
        clip-path: polygon(65% 35%, 100% 0, 100% 100%, 65% 65%);
        background-color: yellow;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .dpart-bottom {
        clip-path: polygon(35% 65%, 65% 65%, 100% 100%, 0 100%);
        background-color: yellowgreen;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .dpart-center {
        clip-path: polygon(35% 35%, 65% 35%, 65% 65%, 35% 35%);
        background-color: white;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* .dparts::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        clip-path: inherit;
        /* Inherit the shape */
    /*  background-color: rgba(0, 0, 0, 0.5);
        /* Shadow color */
    /*    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.5);
        /* Shadow effect */
    /*   z-index: -1;
        /* Place behind the triangle */
    /*   } */

    /* Adding borders using pseudo-elements */
    /* .dparts::before {
        content: '';
        position: absolute;
        top: -2px;
        /* Adjust for border width */
    /* left: -2px;
        /* Adjust for border width */
    /*   right: -2px;
        /* Adjust for border width */
    /*  bottom: -2px;
        /* Adjust for border width */
    /*  clip-path: inherit;
        /* Inherit the triangle shape */
    /*  background-color: rgb(164, 212, 235);
        /* Border color */
    /*    z-index: -1;
        z-index: 0;
        /* Place behind the triangle */
    /* } */

    .triangle {
        position: relative;
        width: 200px;
        /* Base width of the triangle */
        height: 200px;
        /* Height of the triangle */
        background-color: yellow;
        /* Background color of the triangle */
        clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
        /* Triangle shape */
        z-index: 999;
    }

    /* Adding the red border */
    /* .triangle::before {
        content: '';
        position: absolute;
        top: -5px;
        /* Adjust for border thickness */
    /*    left: -5px;
        /* Adjust for border thickness */
    /*    right: -5px;
        /* Adjust for border thickness */
    /*     bottom: -5px;
        /* Adjust for border thickness */
    /* background-color: red; */
    /*      background-color: transparent;
        /* Transparent background */
    /*      border: 5px solid red;
        /* Border color and thickness */
    /*    top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        /* Border color */
    /*    clip-path: inherit;
        /* Inherit the triangle shape */
    /*  z-index: -1;
        /* Place behind the triangle */
    /*  } */

    .triangle::before {
        content: '';
        position: absolute;
        top: -5px;
        /* Adjust for border thickness */
        left: -5px;
        /* Adjust for border thickness */
        right: -5px;
        /* Adjust for border thickness */
        bottom: -5px;
        /* Adjust for border thickness */
        background-color: transparent;
        /* Transparent background */
        border: 5px solid red;
        /* Border color and thickness */
        clip-path: inherit;
        /* Inherit the triangle shape */
        z-index: -1;
        /* Place behind the triangle */
    }

    .tooth-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        background-color: #fff;
        border: 2px solid #ccc;
    }

    .tparts {
        width: 0;
        height: 0;
        position: absolute;
    }

    .tpart-left {
        border-left: 100px solid transparent;
        border-bottom: 100px solid red;
        /* Triangle color */
        top: 0;
        left: 0;
    }

    .tpart-right {
        border-right: 100px solid transparent;
        border-bottom: 100px solid blue;
        /* Triangle color */
        top: 0;
        right: 0;
    }

    .tpart-top {
        border-left: 100px solid transparent;
        border-top: 100px solid green;
        /* Triangle color */
        bottom: 0;
        left: 0;
    }

    .tpart-bottom {
        border-right: 100px solid transparent;
        border-top: 100px solid yellow;
        /* Triangle color */
        bottom: 0;
        right: 0;
    }
</style>

<div class=" row">
    <div class="box bg-white">
        <div class="box-body">
            <div class="d-none img-body">
                <div class="sqaure-wrapper">
                    <div class="triangle top-left"></div>
                    <div class="triangle top-right"></div>
                    <div class="triangle bottom-left"></div>
                    <div class="triangle bottom-right"></div>
                </div>
            </div>

            <div class="d-none tooth-wrapper">
                <div class="tparts tpart-left"></div>
                <div class="tparts tpart-right"></div>
                <div class="tparts tpart-top"></div>
                <div class="tparts tpart-bottom"></div>
            </div>

            <div class="d-none triangle"></div>


            <div class="dparts-wrapper">
                <div class="dparts part-left"></div>
                <div class="dparts part-top"></div>
                <div class="dparts part-right"></div>
                <div class="dparts part-bottom"></div>
            </div>

            <div class="dparts-wrapper">
                <div class="dparts dpart-left"></div>
                <div class="dparts dpart-top"></div>
                <div class="dparts dpart-right"></div>
                <div class="dparts dpart-bottom"></div>
                <div class="dparts dpart-center"></div>
            </div>

        </div>
    </div>
</div>
