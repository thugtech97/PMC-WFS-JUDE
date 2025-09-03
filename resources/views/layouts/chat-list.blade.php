<aside class="right-sidebar">
    <div class="sidebar-chat" data-plugin="chat-sidebar">
        <div class="sidebar-chat-info pt-4">
            <h6 class="position-relative">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.872 9.687 20 6.56 17.44 4 4 17.44 6.56 20 16.873 9.687Zm0 0-2.56-2.56M6 7v2m0 0v2m0-2H4m2 0h2m7 7v2m0 0v2m0-2h-2m2 0h2M8 4h.01v.01H8V4Zm2 2h.01v.01H10V6Zm2-2h.01v.01H12V4Zm8 8h.01v.01H20V12Zm-2 2h.01v.01H18V14Zm2 2h.01v.01H20V16Z"/>
                </svg>
                <b>Choose a Theme</b>
                <button class="theme-close-btn right-sidebar-toggle" title="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                </button>
            </h6>
        </div>
        <div class="chat-list">
            <div class="list-group row">
                <a onclick="switch_theme('sky')" class="list-group-item cursor-pointer">
                    <span class="blue-sky-icon">&nbsp;</span>
                    <span class="name">Blue Sky</span>
                </a>
                <a onclick="switch_theme('earth')" class="list-group-item cursor-pointer">
                    <span class="great-earth-icon">&nbsp;</span>
                    <span class="name">Great Earth</span>
                </a>
                <a onclick="switch_theme('water')" class="list-group-item cursor-pointer">
                    <span class="water-wave-icon">&nbsp;</span>
                    <span class="name">Water Wave</span>
                </a>
                <a onclick="switch_theme('fire')" class="list-group-item cursor-pointer">
                    <span class="flame-red-icon">&nbsp;</span>
                    <span class="name">Flame Red</span>
                </a>
            </div>
        </div>
    </div>
</aside>

<script type="text/javascript">
    function switch_theme(val) {

        let root = document.getElementById("wfs-wrapper");

        switch (val) {
            case  'sky' :
                root.setAttribute('data-theme', 'sky-theme')
                localStorage.setItem('theme','sky-theme');
            break;

            case 'earth' :
                root.setAttribute('data-theme', 'earth-theme')
                localStorage.setItem('theme','earth-theme');
            break;

            case 'water' :
                root.setAttribute('data-theme', 'water-theme')
                localStorage.setItem('theme','water-theme');
            break;

            case 'fire' :
                root.setAttribute('data-theme', 'fire-theme')
                localStorage.setItem('theme','fire-theme');
            break;

            default :

        }

    }
</script>