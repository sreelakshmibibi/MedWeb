<style>
    .parent {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        grid-template-rows: repeat(2, 1fr);
        grid-column-gap: 1px;
        grid-row-gap: 1px;
        grid-template-columns: repeat(12, 50px);
        grid-template-rows: repeat(2, 1fr);
    }

    .tdiv1 {
        grid-area: 1 / 1 / 2 / 7;
    }

    .tdiv2 {
        grid-area: 1 / 7 / 2 / 13;
    }

    .tdiv3 {
        grid-area: 2 / 7 / 3 / 13;
    }

    .tdiv4 {
        grid-area: 2 / 1 / 3 / 7;
    }

    .tdiv5 {
        grid-area: 1 / 1 / 2 / 2;
    }

    .tdiv6 {
        grid-area: 1 / 2 / 2 / 3;
    }

    .tdiv7 {
        grid-area: 1 / 3 / 2 / 4;
    }

    .tdiv8 {
        grid-area: 1 / 4 / 2 / 5;
    }

    .tdiv9 {
        grid-area: 1 / 5 / 2 / 6;
    }

    .tdiv10 {
        grid-area: 1 / 6 / 2 / 7;
    }

    .tdiv11 {
        grid-area: 1 / 7 / 2 / 8;
    }

    .tdiv12 {
        grid-area: 1 / 8 / 2 / 9;
    }

    .tdiv13 {
        grid-area: 1 / 9 / 2 / 10;
    }

    .tdiv14 {
        grid-area: 1 / 10 / 2 / 11;
    }

    .tdiv15 {
        grid-area: 1 / 11 / 2 / 12;
    }

    .tdiv16 {
        grid-area: 1 / 12 / 2 / 13;
    }

    .tdiv17 {
        grid-area: 2 / 1 / 3 / 2;
    }

    .tdiv18 {
        grid-area: 2 / 2 / 3 / 3;
    }

    .tdiv19 {
        grid-area: 2 / 3 / 3 / 4;
    }

    .tdiv20 {
        grid-area: 2 / 4 / 3 / 5;
    }

    .tdiv21 {
        grid-area: 2 / 5 / 3 / 6;
    }

    .tdiv22 {
        grid-area: 2 / 6 / 3 / 7;
    }

    .tdiv23 {
        grid-area: 2 / 7 / 3 / 8;
    }

    .tdiv24 {
        grid-area: 2 / 8 / 3 / 9;
    }

    .tdiv25 {
        grid-area: 2 / 9 / 3 / 10;
    }

    .tdiv26 {
        grid-area: 2 / 10 / 3 / 11;
    }

    .tdiv27 {
        grid-area: 2 / 11 / 3 / 12;
    }

    .tdiv28 {
        grid-area: 2 / 12 / 3 / 13;
    }

    .tflex-container {
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        align-content: center;
        display: flex;
        /* background-color: #bbdefb; */
        height: 100%;
        padding: 15px;
        gap: 5px;

    }

    /* .tflex-items:nth-child(1) { */
    .timage {
        display: block;
        flex-grow: 1;
        flex-shrink: 1;
        flex-basis: auto;
        align-self: auto;
        order: 0;
        flex-grow: 3;
        width: 35px;
        /* Adjust the width as per your requirement */
        height: auto;

        overflow: hidden;
        display: inline-block;
        /* Ensure circle doesn't expand unnecessarily */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    /* .tflex-items:nth-child(2) { */
    .tsquare {
        display: block;
        flex-grow: 0;
        flex-shrink: 1;
        flex-basis: auto;
        align-self: auto;
        order: 0;
        width: 35px;
        height: auto;
        width: 30px;
    }

    .parentdiv {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: repeat(2, 0.5fr);
        grid-column-gap: 1px;
        grid-row-gap: 1px;
        grid-template-columns: 50px;
        grid-template-rows: repeat(2, 25px);
    }

    .childdiv1 {
        grid-area: 1 / 1 / 2 / 2;
    }

    .childdiv2 {
        grid-area: 2 / 1 / 3 / 2;
    }
</style>
<div class="box">
    <div class="box-body">
        <row class="bb-1">
            <div class="col be-1">
                <div class="row">
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col bs-1">
                <div class="row">
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                </div>
            </div>
        </row>
        <row class="bt-1">
            <div class="col">
                <div class="row">
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                    <div class="col f-flex flex-col">
                        <div>
                            <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                                class="h-60 w-50">
                        </div>
                        <div> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                        </div>

                    </div>
                </div>
            </div>
        </row>
    </div>
</div>
<div class="box">
    <div class="box-body">
        <div class="parent">
            <div class=" tdiv1 be-1 bb-1 p-2">
            </div>
            <div class=" tdiv2 bs-1 bb-1 p-2"> </div>
            <div class=" tdiv3 bs-1 bt-1 p-2"> </div>
            <div class=" tdiv4 be-1 bt-1 p-2"> </div>
            <div class="tdiv5 tflex-container">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>

            </div>
            <div class="tflex-container tdiv6">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv7">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv8">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv9">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv10">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv11">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv12">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv13">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv14">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv15">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv16">
                <div class="tflex-items timage">
                    <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                        class="h-60 w-50">
                </div>
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                </div>
            </div>
            <div class="tflex-container tdiv17">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv18">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv19">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv20">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv21">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv22">
                <div class="tflex-items tsquare"> <img id="tt11" src="{{ asset('images/teeths/Asset 5.svg') }}"
                        alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv23">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv24">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv25">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv26">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv27">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
            <div class="tflex-container tdiv28">
                <div class="tflex-items tsquare"> <img id="tt11"
                        src="{{ asset('images/teeths/Asset 5.svg') }}" alt="">
                    <div class="tflex-items timage">
                        <img id="t11" src="{{ asset('images/teeths/Asset 22.svg') }}" alt=""
                            class="h-60 w-50" style="transform: rotate(180deg)">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
