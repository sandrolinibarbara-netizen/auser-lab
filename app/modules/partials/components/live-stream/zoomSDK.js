const meetingSDKElement = document.getElementById('meetingSDKElement');
let leave;

if(type === 'event') {
    leave = root + 'watch?live=event&id=' + lesson
} else {
    leave = root + 'watch?live=stream&id=' + lesson
}

if(meetingSDKElement) {
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareWebSDK();
    ZoomMtg.i18n.load("it-IT");
    const waitingElement = document.getElementById('wait-meeting');
    const joinButton = document.getElementById('join-meeting');
    const waitButton = document.getElementById('wait-connection');
    const errorMessage = document.getElementById('error-message');
    document.getElementById('zmmtg-root').classList.add('whiteBg');
    // Nebbia endpoint
    // const authEndpoint = 'https://zoom-meeting-sdk-auth-sample-lejy.onrender.com';
    // Auser endpoint
    const authEndpoint = 'https://git.heroku.com/auser-zoom.git';
    const sdkKey = document.getElementById('zoom-sdkkey').value;
    const meetingNumber = $('#zoom-meeting').val().split(' ').join('');
    const pw = $('#zoom-pw').val();
    const username = $('#user-fullname').val();
    const role = 0;
    const registrantToken = '';
    const zakToken = '';
    const leaveUrl = leave;

    function getSignature() {
        joinButton.classList.add('d-none');
        if(!errorMessage.classList.contains('d-none')) {
            errorMessage.classList.add('d-none');
        }
        waitButton.classList.remove('d-none');
        fetch(authEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                meetingNumber: meetingNumber,
                role: role,
            })
        }).then((response) => {
            return response.json()
        }).then((data) => {
            console.log(data)
            startMeeting(data.signature)
        }).catch((error) => {
            console.log(error);
            waitButton.classList.add('d-none');
            errorMessage.classList.remove('d-none');
            const p = errorMessage.getElementsByClassName('error-content')[0];
            p.textContent = error;
        })
    }

    function startMeeting(signature) {
        waitingElement.classList.add('d-none');
        ZoomMtg.init({
            leaveUrl: leaveUrl,
            patchJsMedia: true,
            leaveOnPageUnload: true,
            success: (success) => {
                console.log(success);
                ZoomMtg.join({
                    signature: signature,
                    sdkKey: sdkKey,
                    meetingNumber: meetingNumber,
                    passWord: pw,
                    userName: username,
                    // userEmail: userEmail,
                    tk: registrantToken,
                    zak: zakToken,
                    success: (success) => {
                        console.log(success);
                        document.getElementById('upper-menu').classList.add('d-none');
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            },
            error: (error) => {
                console.log(error);
                waitingElement.classList.remove('d-none');
                meetingBox.classList.add('d-none');
            }
        });
    }

    // function startMeeting(signature) {
    //     waitingElement.classList.add('d-none');
    //     meetingBox.classList.remove('d-none');
    //     client.init({
    //         zoomAppRoot: meetingSDKElement, language: 'en-US', patchJsMedia: true, customize: {
    //             video: {
    //                 isResizable: true,
    //                 viewSizes: {
    //                     default: {
    //                         width: window.innerWidth - 128,
    //                         height: window.innerHeight - 128,
    //                     },
    //                 }
    //             },
    //             chat: {
    //                 popper: {
    //                     anchorElement: meetingChat,
    //                     placement: 'bottom',
    //                 }
    //             }
    //         }
    //     }).then(() => {
    //         client.join({
    //             signature: signature,
    //             sdkKey: sdkKey,
    //             meetingNumber: meetingNumber,
    //             password: pw,
    //             userName: username,
    //             tk: registrantToken,
    //             zak: zakToken
    //
    //         }).then(() => {
    //
    //             client.updateVideoOptions({
    //                 viewSizes: {
    //                     default: {
    //                         width: window.innerWidth - 128,
    //                         height: window.innerHeight - 128
    //                     }
    //                 }
    //             });
    //
    //         }).catch((error) => {
    //             waitingElement.classList.remove('d-none');
    //             meetingBox.classList.add('d-none');
    //             console.log(error)
    //         })
    //     }).catch((error) => {
    //         waitingElement.classList.remove('d-none');
    //         meetingBox.classList.add('d-none');
    //         console.log(error)
    //     })
    // }
}