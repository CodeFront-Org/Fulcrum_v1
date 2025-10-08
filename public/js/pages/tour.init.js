"use strict";
$(document).ready(function() {
    hopscotch.startTour({
        id: "my-intro",
        steps: [{
                target: "logo-tour",
                title: "Welcome",
                content: "Great to have you on-board. Finish E-KYC to start Investing in Solar Energy.",
                placement: "bottom",
                yOffset: 10,
                xOffset: -105,
                arrowOffset: "center"
            },

            {
                target: "heading-title-tour",
                title: "E-KYC Verification",
                content: "Complete the following step to buy solar cells:",
                placement: "top",
                yOffset: 10,
                xOffset: -105,
                arrowOffset: "center"
            },
            { target: "blockquote-title-tour", title: "User settings", content: "You can edit you profile info here.", placement: "bottom", zindex: 999 }, {
                target: "thankyou-tour",
                title: "E-KYC Verification",
                content: "Complete the following step to buy solar cells:",
                placement: "top",
                zindex: 999,
                yOffset: -10,
                xOffset: -105,
                arrowOffset: "center"
            }
        ],
        showPrevButton: !0
    })
});