////////////////////////////////////////////////////
// VIDEO POKER CLASSIC
// @AUTHOR: Robert Welliever
// @RELEASE: May 2017
// @LICENSE: Public Domain, No Restrictions
////////////////////////////////////////////////////
var GameStates = { // Game state enumeration
    Uninitialized: 0,
    FirstDeal: 1,
    SecondDeal: 2,
    HandLost: 3,
    HandWon: 4,
    GameOver: 5
}

var _GameState = GameStates.Uninitialized; // Initial game state
var _Canvas = document.getElementById('cardCanvas'); // Canvas contains the five card renderings
var _StartCredits = 100; // Number of starting credits
var _Credits = _StartCredits; // Number of current credits
var _CurrentBet = 1; // Amount of bet
var _WinID = -1; // Winning ID of prize if Hand is winning
var _Hand; // The currently dealt Hand object
var _HandX; // Horizontal coordinate that the Cards are pinned to the Canvas
var _BlinkOn = false; // Whether or not the flashing effect from a win is currently flashing on or off.
var _PrizeWinThread; // Interval function handling marquee flashing on winning Hand
var _NumResourcesToLoad = 15; // The total number of assets that require loading before play initializes 

var Deck = { // Deck Object - A 52 card poker deck
    Cards: null, // Array of Card objects representing one deck
    Shuffled: null, // Array of Card objects representing a shuffled deck
    SpriteSheet: null, // Image object of uncut card deck
    SpriteWidth: 230, // pixel width of card in source image
    SpriteHeight: 300, // pixel height of card in source image
    Initialize: function () { // 
        this.Cards = new Array(); // The deck
        for (var id = 0, i = 0; i < 4; i++) // for each Suit {Clubs,Diamonds,Hearts,Spades}
            for (var j = 0; j < 13; j++) // for each Rank {A,2,...,10,J,Q,K}
                this.Cards.push(new Card((++id).toString(), i + 1, j + 1, j * this.SpriteWidth, i * this.SpriteHeight, this.SpriteWidth, this.SpriteHeight)); // Card(id, suit, rank, x, y, width, height)
        this.SpriteSheet = document.createElement("img"); // 52 card deck
        this.SpriteSheet.src = "Image/Deck.png";
        this.SpriteSheet.onload = _OnResourceLoaded;
    },
    Shuffle: function () { // Sets cards in random order
        this.Shuffled = new Array(); // Flush all Cards in Deck
        var cardIDs = new Array(); // Put card indices in list
        for (var i = 0; i < 52; i++)
            cardIDs.push(i);
        while (cardIDs.length != 0) { // Pull indices randomly until none remain
            var randomIndex = Math.floor(Math.random() * cardIDs.length);
            var cardID = cardIDs.splice(randomIndex, 1); // take a value randomly
            var card = this.Cards[cardID];
            card.Locked = false; // Clear any previous lock on Card
            card.FlipState = 0; // Cards dealt face down
            this.Shuffled.push(card);
        }
    },
    Deal: function (numCards) {
        var dealt = new Array(); // Array of Card objects
        for (var i = 0; i < numCards; i++)
            dealt.push(this.Shuffled.pop());
        return dealt;
    }
}

function Hand(cards) { // Hand object - The player's active Card objects
    this.Cards = cards; // Array of Card objects
    this.Evaluate = function () { // Return ID of winning hand type, or -1 if losing hand
        var isRoyal = this.IsRoyal();
        var isFourOfAKind = this.IsFourOfAKind();
        var isFullHouse = this.IsFullHouse();
        var isFlush = this.IsFlush();
        var isStraight = this.IsStraight();
        var isThreeOfAKind = this.IsThreeOfAKind();
        var isTwoPair = this.IsTwoPair();
        var isJacksOrBetter = this.IsJacksOrBetter();

        if (isStraight && isFlush && isRoyal) return 9; // royal flush
        else if (isStraight && isFlush && !isRoyal) return 8; // straight flush
        else if (isFourOfAKind) return 7;// four of a kind
        else if (isFullHouse) return 6;// full house
        else if (isFlush) return 5;// flush
        else if (isStraight) return 4;// straight
        else if (isThreeOfAKind) return 3;// 3 of a kind
        else if (isTwoPair) return 2; // two pair
        else if (isJacksOrBetter) return 1;// jacks or better
        return -1;
    }
    this.IsRoyal = function () { // true if card ranks: 10, 11, 12, 13, 1 (Ten, Jack, Queen, King, Ace)
        var sorted = this.Sort();
        return sorted[0].Rank === 1 &&
            sorted[1].Rank === 10 &&
            sorted[2].Rank === 11 &&
            sorted[3].Rank === 12 &&
            sorted[4].Rank === 13;
    }
    this.IsFullHouse = function () { // true if three card ranks equal with remaining two card ranks equal
        var sorted = this.Sort();
        return (sorted[0].Rank === sorted[1].Rank && sorted[2].Rank === sorted[4].Rank) ||
            (sorted[0].Rank === sorted[2].Rank && sorted[3].Rank === sorted[4].Rank);
    }
    this.IsFourOfAKind = function () { // true if four card ranks equal
        var sorted = this.Sort();
        return sorted[0].Rank === sorted[3].Rank || sorted[1].Rank === sorted[4].Rank;
    }
    this.IsFlush = function () // true if all suits equal
    {
        for (var i = 1; i < this.Cards.length; i++)
            if (this.Cards[i].Suit !== this.Cards[0].Suit)
                return false;
        return true;
    }
    this.IsStraight = function () { // true if successively incremented ranks (n, n + 1,... n + 4) or royal (10, 11, 12, 13, 1)
        if (this.IsRoyal()) // royal straight
            return true;
        var sorted = this.Sort();
        for (var i = 0; i < sorted.length - 1; i++)
            if (sorted[i].Rank !== (sorted[i + 1].Rank - 1))
                return false;
        return true;
    }
    this.IsThreeOfAKind = function () {
        var CardCounts = {}; // Rank frequencies
        for (var i = 0; i < this.Cards.length; i++) {
            if (CardCounts.hasOwnProperty(this.Cards[i].Rank)) // Check if key exists in hashmap
                CardCounts[this.Cards[i].Rank]++; // Increment frequency
            else
                CardCounts[this.Cards[i].Rank] = 1; // First occurence
        }
        for (var i = 1; i < 14; i++) // Check each rank, 1-13 (Ace-King)
            if (CardCounts.hasOwnProperty(i) && CardCounts[i] >= 3) // at least triple of a rank
                return true;
        return false;
    }
    this.IsTwoPair = function () {
        var CardCounts = {}; // Rank frequencies
        for (var i = 0; i < this.Cards.length; i++) {
            if (CardCounts.hasOwnProperty(this.Cards[i].Rank)) // Check if key exists in hashmap
                CardCounts[this.Cards[i].Rank]++; // increment frequency
            else
                CardCounts[this.Cards[i].Rank] = 1; // first occurence
        }
        var hasPair = false;
        for (var i = 1; i < 14; i++) { // Check each rank, 1-13 (Ace-King)
            if (CardCounts.hasOwnProperty(i) && CardCounts[i] === 4) // four of a kind is arguably a special case of two pair
                return true;
            else if (CardCounts.hasOwnProperty(i) && CardCounts[i] >= 2) { // at least double of a rank
                if (!hasPair)
                    hasPair = true;
                else
                    return true;
            }
        }
        return false;
    }
    this.IsJacksOrBetter = function () {
        var CardCounts = {}; // Rank frequencies
        for (var i = 0; i < this.Cards.length; i++) {
            var rank = this.Cards[i].Rank;
            if (rank === 11 || rank === 12 || rank === 13 || rank === 1) { // Rank is Jack or better
                if (CardCounts.hasOwnProperty(rank))
                    CardCounts[rank]++; // Increment frequency
                else
                    CardCounts[rank] = 1; // First occurence
            }
        }
        for (var i = 1; i < 14; i++) // Check each rank, 1-13 (Ace-King)
            if (CardCounts.hasOwnProperty(i) && CardCounts[i] >= 2)
                return true;
        return false;
    }
    this.Sort = function () { // Returns new array in sorted rank, precedence: 1-13 (Ace-King)
        var sorted = new Array();
        for (var i = 0; i < this.Cards.length; i++)
            sorted.push(this.Cards[i]);
        sorted.sort(function (card1, card2) { return card1.Rank - card2.Rank }); // anonymous comparator function
        return sorted;
    }
}

function Card(id, suit, rank, x, y, width, height) { // Card object - Represents a standard playing card.
    this.ID = id; // Card ID: 1-52
    this.Suit = suit; // Card Suit: 1-4 {Club, Diamond, Heart, Spade}
    this.Rank = rank; // Card Rank: 1-13 {Ace, Two, ..King}
    this.X = x; // Horizontal coordinate position of card image on sprite sheet
    this.Y = y; // Vertical coordinate position of card image on sprite sheet
    this.Width = width; // Pixel width of card sprite
    this.Height = height; // Pixel height of card sprite
    this.Locked = false; // true if Card is Locked/Held
    this.FlipState = 0; // The flip state of card: 0 or 1 (Back Showing or Face Showing)
}

function _NewGame() { // Start a new game
    document.getElementById('divLoadScreen').style.display = 'none'; // Ensure loading popover hidden
    document.getElementById('divGameOver').style.display = 'none'; // Ensure game over popover hidden
    document.getElementById('btnDeal').innerHTML = 'DEAL';
    _GameState = GameStates.FirstDeal;
    _ResetPrizeWin(); // Remove any previous win
    _MeasureUI(); // Measure out dynamically sized components
    Deck.Shuffle();
    _Hand = new Hand(Deck.Deal(5)); // Deal five cards
    _Credits = _StartCredits;
    _DrawScreen();
}

function _DealClick() { // Deal button click event handler
    _ResetPrizeWin();
    if (_GameState === GameStates.GameOver) { // Restart game
        GameAudio.Play('Deal'); // Use Deal sound also signal game restart
        document.getElementById('divGameOver').style.display = 'none';
        document.getElementById('btnDeal').innerHTML = 'DEAL';
        document.getElementById("btnBetDown").className = document.getElementById("btnBetUp").className = "BUTTON ROUNDED";
        for (var i = 0; i < _Hand.Cards.length; i++) // Flip last losing hand face down to signify new game
            _Hand.Cards[i].FlipState = 0;
        _Credits = _StartCredits;
        _GameState = GameStates.FirstDeal; // Next state
    }
    else if (_GameState === GameStates.FirstDeal || _GameState === GameStates.HandWon || _GameState == GameStates.HandLost) { // Deal first Hand
        Deck.Shuffle(); // Use fresh Deck every Hand
        GameAudio.Play('Deal');
        _Hand = new Hand(Deck.Deal(5)); // Deal five cards
        for (var i = 0; i < _Hand.Cards.length; i++) // Flip each card face up
            _Hand.Cards[i].FlipState = 1; 
        _Credits -= _CurrentBet; // Collect user's bet
        _GameState = GameStates.SecondDeal; // Next state
    }
    else if (_GameState === GameStates.SecondDeal) // Deal second Hand
    {
        for (var i = 0; i < _Hand.Cards.length; i++) {
            if (_Hand.Cards[i].Locked) // Do not discard cards locked by the user
                continue;
            _Hand.Cards[i] = Deck.Deal(1)[0]; // Deal one Card
            _Hand.Cards[i].FlipState = 1; // Ensure Card face showing
        }
        _ProcessHand(); // Do checks for win/loss/game over
    }
    _DrawScreen();
}

function _OnCanvasClick(e) { // Canvas (player's Hand) click event handler
    if (_GameState !== GameStates.SecondDeal) // Card interaction available on second deal only
        return;
    e = e || window.event;
    e.preventDefault();
    for (var i = 0; i < 5; i++) { // Check collision/intersection of mouse click on each Card
        var cardX = _HandX + (i * (_CardWidth + 4) + 4); // 4px buffer
        if (e.offsetX >= cardX && // Inside left border
            e.offsetX <= cardX + _CardWidth // Inside right border
            && e.offsetY >= 0 // Below card top
            && e.offsetY <= _CardHeight) // Above card bottom
        {
            _Hand.Cards[i].Locked = !_Hand.Cards[i].Locked; // Collision detected, toggle card lock
            if (_Hand.Cards[i].Locked)
                GameAudio.Play('Hold');
            else
                GameAudio.Play('Unhold');
        }
    }
    _DrawScreen();
}

function _Bet(action) {
    if (_GameState !== GameStates.FirstDeal &&
        _GameState !== GameStates.HandWon &&
        _GameState !== GameStates.HandLost)
        return; // Only allow bet before being dealt

    if (action === '-') { // Bet down requested
        if (_CurrentBet > 1) { // Govern minimum bet
            _CurrentBet -= 1; // Decrement bet
            GameAudio.Play('BetDown');
        }
    }
    else if (action === '+') { // Bet up requested
        if (_CurrentBet < 5 && _CurrentBet < _Credits) { // Govern maximum bet
            _CurrentBet += 1; // Increment bet
            GameAudio.Play('BetUp');
        }
    }
    _UpdateBetLabel();
    _UpdateCreditsLabel();
}

function _ProcessHand() { // Process a Hand checking for win, loss, or game over
    var winID = _Hand.Evaluate();
    if (winID != -1) { // Woo-Hoo, a winning hand
        _GameState = GameStates.HandWon;
        _Credits += _GetPrizeAmount(winID); // Add winnings
        _WinID = winID; // Set prize row for marquee blinking effect
        _PrizeWinThread = setInterval(_PrizeWinBlink, 400); // Start blinking (400 ms)
        GameAudio.Play('Win');
    }
    else { // Losing Hand
        if (_Credits === 0) { // Check for termination condition (0 Credits)
            _GameState = GameStates.GameOver;
            document.getElementById('divGameOver').style.display = 'block';
            document.getElementById('btnDeal').innerHTML = 'RESTART';
            return;
        }
        _GameState = GameStates.HandLost;
        if (_CurrentBet > _Credits) // Ensure sufficient credits for bet
            _CurrentBet = _Credits; // Reduce bet to match remaining credits
    }
}

function _GetPrizeAmount(winID) { // Payout schedule
    if (winID === 9 && _CurrentBet === 5) return 4000; // royal flush max bet
    else if (winID === 9) return 250 * _CurrentBet; // royal flush
    else if (winID === 8) return 50 * _CurrentBet; // straight flush
    else if (winID === 7) return 25 * _CurrentBet; // four of a kind
    else if (winID === 6) return 9 * _CurrentBet; // full house
    else if (winID === 5) return 6 * _CurrentBet; // flush
    else if (winID === 4) return 4 * _CurrentBet; // straight
    else if (winID === 3) return 3 * _CurrentBet; // three of a kind
    else if (winID === 2) return 2 * _CurrentBet; // two pair
    else if (winID === 1) return 1 * _CurrentBet; // jacks or better
}

function _DrawScreen() { // Render UI update
    if (_GameState == GameStates.Uninitialized) // Redrawn only if loading screen is down
        return;
    var g = _Canvas.getContext('2d'); // Graphics context
    g.clearRect(0, 0, _Canvas.width, _Canvas.height); // Wipe frame clean
    for (var i = 0; i < _Hand.Cards.length; i++) { // for each Card in Hand
        if (_Hand.Cards[i].FlipState === 1)
            _DrawCardFace(g, i); // FlipState == 1
        else
            _DrawCardBack(g, i); // FlipState == 0

        if (_GameState === GameStates.SecondDeal && _Hand.Cards[i].Locked) // Second deal
            _DrawCardHold(g, i); // Card is locked by player
    }
    _UpdateBetLabel(); // Refresh html bet elements
    _UpdateCreditsLabel(); // Refresh html credits elements

    if (_GameState == GameStates.HandLost || _GameState == GameStates.HandWon)
        _DrawHandOverMessage(g);
}

function _UpdateCreditsLabel() {
    document.getElementById('divCredits').innerHTML = this._Credits.toString() + ' CREDITS';
}

function _UpdateBetLabel() {
    if (_GameState === GameStates.FirstDeal || _GameState === GameStates.HandWon || _GameState == GameStates.HandLost) // Betting buttons lit up and active
        document.getElementById("btnBetDown").className = document.getElementById("btnBetUp").className = "BUTTON ROUNDED";
    else if (_GameState === GameStates.SecondDeal || _GameState === GameStates.GameOver) // Betting buttons subdued and inactive
        document.getElementById("btnBetDown").className = document.getElementById("btnBetUp").className = "BUTTON_OFF ROUNDED";

    document.getElementById('lblCurrBet').innerHTML = 'BET ' + _CurrentBet.toString();
}

function _PrizeWinBlink() // Handles marquee blink on winning prize row
{
    _BlinkOn = !_BlinkOn; // Toggle the effect
    var rowStyle = document.getElementById('row' + _WinID).style; // The winning prize row's style property
    rowStyle.color = _BlinkOn ? '#fff' : '#fc5'; // white to yellow
    rowStyle.textShadow = _BlinkOn ? '0 0 1px #fff' : '0 0 10px #a70'; // Toggle small white to large yellow shadow
}

function _ResetPrizeWin() {
    if (_PrizeWinThread != null) // Stop blinking
        clearInterval(_PrizeWinThread); // Terminate any running thread
    if (_WinID !== -1) { // Party is over, back to normal row style
        var rowStyle = document.getElementById('row' + _WinID).style;
        rowStyle.textShadow = '';
        rowStyle.color = '#fff';
        _WinID = -1; // Reset prize win
    }
}

function _MeasureUI() // Set position and size rendering information for UI elements
{
    // Remove scrollbars from browser interface
    var docStyle = document.documentElement.style;
    docStyle.overflow = 'hidden';
    docStyle.overflowX = 'hidden';
    docStyle.overflowY = 'hidden';
    document.body.scroll = 'no'; // IE only

    var windowWidth = window.innerWidth < 480 ? 480 : window.innerWidth; // get min game width

    // Set Card drawn width
    _CardWidth = Math.floor(windowWidth / 7); // Drawn card size is proportional to window size
    if (_CardWidth < 60) _CardWidth = 60; // Govern min pixel width of drawn card
    else if (_CardWidth > 200) _CardWidth = 200; // Govern max pixel width of drawn card

    // Set Card drawn height
    _CardHeight = Math.floor((Deck.SpriteHeight / Deck.SpriteWidth) * _CardWidth); // drawn card height calculated to maintain proportion

    // Set drawn Hand dimensions & position
    _Canvas.height = _CardHeight + 5; // Set canvas height to card height plus buffer
    _Canvas.width = windowWidth; // Stretch canvas to width of window
    _HandX = Math.floor((_Canvas.width - (_CardWidth * 5)) / 2) - 10;

    // Set marquee width & font size
    var marqueeFontSize = _CardWidth / 8; // Prizes Font Sizing
    if (marqueeFontSize < 16) marqueeFontSize = 16; // Govern min font size
    else if (marqueeFontSize > 30) marqueeFontSize = 30; // Govern max font size
    document.getElementById('tblMarquee').style.fontSize = marqueeFontSize.toString() + "px"; // apply font
    document.getElementById('tblMarquee').style.width = ((_CardWidth + 4) * 5) + 'px';

    // Set credits font size
    var creditsFontSize = _CardWidth / 7;
    if (creditsFontSize < 20) creditsFontSize = 20; // Govern min font size
    else if (creditsFontSize > 30) creditsFontSize = 30; // Govern max font size

    var creditsDisplay = document.getElementById('divCredits');
    creditsDisplay.style.fontSize = creditsFontSize.toString() + 'px';
    creditsDisplay.style.lineHeight = (creditsFontSize + 10).toString() + 'px'; // Use line height to pad and vertically center text
    creditsDisplay.style.height = (creditsFontSize + 10).toString() + 'px'; // Match container to line height

    // Keep the game height proportional if user resizes window
    var largerDimension = window.screen.width < window.screen.height ? window.screen.height : window.screen.width;
    document.getElementById('body').style.height = largerDimension + 'px';

    document.getElementById('divGameOver').style.height = largerDimension + 'px';  // Apply change to Game Over screen
}

function _DrawCardFace(g, cardIndex) {
    var card = _Hand.Cards[cardIndex];
    var cardX = _HandX + (cardIndex * (_CardWidth + 4) + 4); // Card x position (4px buffer)
    g.drawImage(Deck.SpriteSheet, card.X, card.Y, Deck.SpriteWidth, Deck.SpriteHeight, cardX, 0, _CardWidth, _CardHeight); // render Card sprite
}

function _DrawCardBack(g, cardIndex) {
    g.save(); // push styling context
    g.fillStyle = '#300'; // Set dark red card back
    var cardX = _HandX + (cardIndex * (_CardWidth + 4) + 4); // Card x position (4px buffer)
    g.fillRect(cardX, 0, _CardWidth, _CardHeight); // Render card back
    g.restore(); // pop styling context
}

function _DrawCardHold(g, cardIndex) {
    g.save(); // push styling context
    var fontHeight = Math.round(_CardWidth / 4); // HOLD text font size
    var fontPad = 5; // HOLD text vertical padding
    g.font = fontHeight + 'px Arial'; // HOLD text font
    var holdHeight = fontHeight + 2 * fontPad; // HOLD container height
    var x = _HandX + (cardIndex * (_CardWidth + 4) + 4); // HOLD container x position (4px buffer)
    var y = Math.round(_CardHeight / 2 - holdHeight / 2); // HOLD container y position
    g.fillStyle = '#111'; // Set blackish background
    g.fillRect(x, y, _CardWidth, holdHeight);  // Draw black background on HOLD element
    g.lineWidth = 2; // gold border width
    g.strokeStyle = '#c90'; // Set gold border color
    g.strokeRect(x, y, _CardWidth, holdHeight); // Draw gold border on HOLD element
    x += Math.round(_CardWidth / 2 - g.measureText('HOLD').width / 2); // HOLD text x position
    y += fontHeight; // HOLD text y position
    g.fillStyle = '#c90'; // Set gold font color
    g.fillText('HOLD', x, y); // Fill HOLD text
    g.strokeText('HOLD', x, y); // Draw outline HOLD text (used to embolden)
    g.restore(); // pop styling context
}

function _DrawHandOverMessage(g) {
    g.save(); // push styling context
    var message = _GameState == GameStates.HandWon ? 'Win' : 'Lose'; // Message text
    var fontSize = Math.round(_CardHeight / 2); // Message font size
    g.font = fontSize.toString() + 'px Arial Black'; // Set message font
    var messageWidth = g.measureText(message).width; // Message width
    var x = Math.round(_Canvas.width / 2 - messageWidth / 2); // Message x position
    var y = Math.round(fontSize + fontSize / 4); // Message y position
    g.globalAlpha = .3; // Set semi-transparent background color
    g.fillStyle = '#000'; // Set black background
    _DrawRoundedRectangle(g, x - 100, y - fontSize, messageWidth + 200, Math.round(fontSize * 1.5), 10, true, false); // Draw round rectangle background
    g.globalAlpha = 1; // Set fully opaque color
    g.fillStyle = _GameState == GameStates.HandWon ? '#c90' : '#E70012'; // Win = gold, Lose = red
    g.strokeStyle = '#000'; // Set black border color
    g.lineWidth = Math.round(_CardHeight / 50);
    g.fillText(message, x, y);
    g.strokeText(message, x, y);
    g.restore(); // pop styling context
}

function _DrawRoundedRectangle(g, x, y, width, height, borderRadius, doFill, doStroke) // Utility for drawing a rounded rectangle to a given graphics context. Args->(graphics, x, y, width, height, borderRadius, doFill, doStroke)
{
    g.beginPath(); // clockwise path
    g.moveTo(x + borderRadius, y); // upper-left after corner
    g.lineTo(x + width - borderRadius, y); // upper-right before corner
    g.quadraticCurveTo(x + width, y, x + width, y + borderRadius); // upper-right rounded corner
    g.lineTo(x + width, y + height - borderRadius); // lower-right before corner
    g.quadraticCurveTo(x + width, y + height, x + width - borderRadius, y + height); // lower-right rounded corner
    g.lineTo(x + borderRadius, y + height); // lower-left before corner
    g.quadraticCurveTo(x, y + height, x, y + height - borderRadius); // lower-left rounded corner
    g.lineTo(x, y + borderRadius); // upper-left before corner
    g.quadraticCurveTo(x, y, x + borderRadius, y); // upper-left rounded corner
    g.closePath();
    if (doStroke) g.stroke();
    if (doFill) g.fill();
}

var GameAudio = {
    _FileNames: ["Hold", "Unhold", "BetUp", "BetDown", "Deal", "Win", "GameOver"], // List of sound effects
    _SoundEffects: {}, // A hashmap of sound effect buffers keyed by sound effect name
    Initialize: function () {
        var audio = new Audio(); // Instantiate Audio object for media type (also called a MIME or Content type) check
        var oggCapable = audio.canPlayType('audio/ogg') !== ''; // Check for OGG Media type
        var mp3Capable = audio.canPlayType('audio/mpeg') !== ''; // Check for MPEG Media type
        var wavCapable = audio.canPlayType('audio/wav') !== ''; // Check for WAV Media type
        var extension = oggCapable ? 'ogg' : mp3Capable ? 'mp3' : wavCapable ? 'wav' : ''; // Set audio type by precendence
        var bufferSize = 2; // double-buffered sounds are suitable for our purposes
        for (var i = 0; i < this._FileNames.length; i++) {
            var buffer = new Array();
            for (var bufferIndex = 0; bufferIndex < bufferSize; bufferIndex++) // Buffered effects are required for "async", overlapping play of same sound
            {
                audio = new Audio('Audio/' + this._FileNames[i] + "." + extension);
                audio.onloadeddata = _OnResourceLoaded;
                buffer.push(audio);
            }
            this._SoundEffects[this._FileNames[i]] = buffer; // Actual buffer
            this._SoundEffects[this._FileNames[i] + "I"] = 0; // Buffer pointer
        }
    },
    Play: function (soundName) {
        if (this._SoundEffects.hasOwnProperty(soundName)) // Check if sound effect is keyed in hashmap
        {
            var buffer = this._SoundEffects[soundName]; // Get the buffer for a particular sound effect
            var bufferIndex = this._SoundEffects[soundName + "I"]; // Get buffer's current index
            bufferIndex = bufferIndex === buffer.length - 1 ? 0 : bufferIndex + 1; // Increment index, or reset if at end
            this._SoundEffects[soundName + "I"] = bufferIndex; // Reset buffer index
            buffer[bufferIndex].play(); // Play sound effect at buffer index
        }
    }
};

function _OnResourceLoaded() { // Fired per loaded resource
    _NumResourcesToLoad--; // Decrement global counter
    if (_NumResourcesToLoad === 0) // All resources loaded, begin game
        _NewGame();
}

window.onresize = function () { // Handle responsive game sizing
    _MeasureUI();
    _DrawScreen();
}

window.onbeforeunload = function () {
    if (_PrizeWinThread != null) // If marquee blinking effect is running when app is closing 
        clearInterval(_PrizeWinThread); // Terminate
};

Deck.Initialize(); // Initialize Deck object to begin load of Sprite Sheet resource
GameAudio.Initialize(); // Initialize Audio objects to begin load of audio files