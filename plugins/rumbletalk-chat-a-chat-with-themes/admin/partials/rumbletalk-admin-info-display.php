<div class="info">
    <div class="shortcode-info">
        <h3>What is a [shortcode]?</h3>
        <p>
            A <b>[shortcode]</b> is a WordPress code that can be used to quickly, and easily, add your chat
            into your pages by simply copying and pasting text (or "short code") directly into your text editor.
            <br>
            Simply copy the line, e.g. "<b style="user-select: all;">[rumbletalk-chat]</b>" into your text editor,
            and RumbleTalk will take care of the rest.
            <br>
            * It is recommended to use your specific chat [shortcode]
            (found under each chat edit box, above this paragraph)
            <br>
            The available attributes for the [shortcode] are:
            <b>hash</b>, <b>width</b>, <b>height</b>, <b>members</b>, <b>floating</b>
        </p>
    </div>

    <ul class="ul-disc">
        <li>
            <b>Chat name</b>:
            The name of the chat - for personal use and SEO
        </li>
        <li>
            <b>Hash</b>:
            This is a unique 8 characters string (can be found in the top right gray area of chat square).
            <br>
            It is populated automatically once you create an account.
        </li>
        <li>
            <b>Width</b>:
            The width (in pixels) of your chat room.
            <br>
            You can set to a percentage (e.g. 40%) or leave blank to fill the width of the page.
        </li>
        <li>
            <b>Height</b>:
            The height of your chat room.
            <br>
            You may <b>not</b> use percentage in height
            <br>
            * If left blank, defaults to 500px
        </li>
        <li>
            <b>Members</b>:
            Automatically logs the members of your community into your chat room (no need to supply user and password).
            <br>
            If you wish to allow registered users, and guests, to log into your chat, you should uncheck the
            <a href="https://www.rumbletalk.com/support/API_Auto_Login/" target="_blank">"Force SDK"</a>
            option in the chat's settings.
        </li>
        <li>
            <b>Login name</b>
            The user's attribute used to log into your RumbleTalk chat.
            <br>
            If the chosen field is empty, the user's "Display name" is used
        </li>
        <li>
            <b>Floating</b>:
            A floating toolbar chat.
            <br>
            It will appear on the right (or left) bottom corner of the page.
            <br>
            Configuring the side can be done in the chat settings under the "Floating" tab.
            <br>

            <button class="anchor hover-image">
                Chat in a page
                <img src="<?= plugins_url('../images/embed-option.jpg', __FILE__) ?>">
            </button>
            <button class="anchor hover-image">
                Floating chat (toolbar)
                <img src="<?= plugins_url('../images/embed-option-floating.jpg', __FILE__) ?>">
            </button>
        </li>
        <li>
            Feel free to
            <a href="https://www.rumbletalk.com/about_us/contact_us/" target="_blank">contact us</a>
            if you have any question
        </li>
    </ul>

    <h3 class="rt-color-dark">How to add your chat to a page:</h3>
    <p class="adding-chat-option">
        <img src="<?= plugins_url('../images/SQ-about.png', __FILE__) ?>">
        Add the exact text
        <b class="rt-color">[rumbletalk-chat]</b>
        to your visual editor where you want your chat to show
        <br>
        ... and you are done.
    </p>

    <p class="adding-chat-option">
        <img src="<?= plugins_url('../images/SQ-contact.png', __FILE__) ?>">
        In case you have more than one chat, you can add a specific chat using
        <br>
        the chat's <b>hash</b> <b class="rt-color">[rumbletalk-chat hash="replace this with the hash"]</b>
    </p>

    <button class="rt-color anchor" id="troubleshooting-header" type="button">Troubleshooting</button>

    <div id="troubleshooting-body">
        <ol>
            <li>
                <b>My chat height is too small, how can I adjust it?</b>
                <p>
                    RumbleTalk chat room is elastic and can expand to any size (even Tv & big screens).
                    <br>
                    Please add a constant width and height to the chat configuration (above).
                </p>
            </li>
            <li>
                <b>Seems like my theme is not working with RumbleTalk plugin?</b>
                <p>
                    This is not so common, but if you run into issues with the plugin, you may want to try a direct
                    embed.
                </p>
                To do it: disable the RumbleTalk WordPress plugin and use a full chat code as shown in the RumbleTalk
                administration panel.
                <br>
                (we supplied it below for ease)
                <br>
                <code>
                    <?= htmlspecialchars(RumbleTalk::embed(false)) ?>
                </code>
            </li>

            <li>
                <b>I want my users to login with their member's credentials, but I see "Private Chat Room" message</b>
                <p>
                    A. Click on the members checkbox in the plugin. <br/>
                    B. Then open the chat settings, verify that "allow guests" is checked. This should allow users to
                    automatically login.
                    Note, this does not mean that guests can login to the chat.
                </p>
            </li>
            <li>
                <b>
                    I want my users to login with their member's credentials, Do I need to add them manually to the
                    RumbleTalk settings?
                </b>
                <p>
                    You do not need to add users manually to make them auto login.
                    However, you do need to add your administrators.
                    <br>
                    Tip: Create a user with the same login name in your system to make it easier for the user to login.
                </p>
            </li>
        </ol>
        <div>
            <p>
                more FAQ can be <a href="https://www.rumbletalk.com/blog/index.php/knowledge-base/">found in the knowledge base</a>
            </p>
        </div>
        <div>
            <h4 class="rt-color">WordPress Hosted</h4>
            Note, If your website is hosted by WordPress, you will not be able to use RumbleTalk :-(
            <br>
            That's because WordPress prevents 3rd party widgets to be included in their <b>hosted</b> version.
        </div>
    </div>
</div>