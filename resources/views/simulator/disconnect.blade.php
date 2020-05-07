<html>
  <head>
    <title>გამოაერთე !</title>
    <link rel="icon" href="{{ asset('images/simulator/connector.png') }}" />
  </head>
  <body>

    <img class="header" src="{{ asset('images/simulator/disconnect.png') }}" />

    <div class="form-wrapper">
      <img src="{{ asset('images/simulator/transistor.png') }}" />
      <input type="number" placeholder="29" id="charger_id"/>
      <button id="btn">Disconnect</button>
      
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token" />
    </div>


    <div class="popup-underling-bg" style="display: none">
      <div class="popup">
        <img class="close" src="{{ asset('images/simulator/close.png') }}" onclick="closeSuccsessPopup()" />

        <div class="text-wrapper">
          <p>კონექტორი წარმატებით</p>
          <p>გამოერთდა დამტენიდან !</p>
        </div>
        <img class="checkmark" src="{{ asset('images/simulator/checkmark.png') }}" />
      </div>
    </div>

    <div class="popup-underling-bg" style="display: none">
      <div class="popup">
        <img class="close" src="{{ asset('images/simulator/close.png') }}" onclick="closeFailurePopup()" />

        <div class="text-wrapper">
          <p>ავტომობილი არ არის</p>
          <p>დამტენთან შეერთებული !</p>
        </div>
        <img class="checkmark" src="{{ asset('images/simulator/efmark.png') }}" />
      </div>
    </div>

  </body>
</html>

<style>

  @font-face{
    font-family: Mtavruli;
    src: url("{{ asset('fonts/bpg_nino_mtavruli_normal.ttf') }}");
  }

  body{
    background: radial-gradient(50% 50% at 50% 50%, #5A32AF 0%, #1F1D6B 100%);
  }

  .header{
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: calc(615rem / 16);
    margin-top: 20vh;
    margin-bottom: 10vh;
  }

  .form-wrapper{
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .form-wrapper img{
    width: calc(271rem / 16);
    margin-left: -3vw;
  }

  .form-wrapper input{
    width: calc(170rem / 16);
    height: calc(67rem / 16);
    background-color: #F2F2F2;
    border-radius: 10px;
    border:1px solid black;
    text-align: center;
    color: black;
    letter-spacing: 1px;
    font-size: calc(20rem / 16);
    padding-left: calc(18rem / 16);
    margin-left: 4vw;
    margin-right: 3vw;
  }

  .form-wrapper input::placeholder{
    color: #BDBDBD;
  }

  .form-wrapper button{
    width: calc(167rem / 16);
    height: calc(58rem / 16);
    border-radius: 10px;
    background-color: #333333;
    border: 1px solid #F2F2F2;
    font-family: sans-serif;
    color: #F2F2F2;
    font-size: calc(20rem / 16);
    letter-spacing: 1px;
  }

  .form-wrapper button:hover{
    cursor: pointer;
  }

  .popup-underling-bg{
    position: fixed;
    left:0;
    top:0;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-color: rgba(8, 8, 34, .9);
  }

  .popup{
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: calc(497rem / 16);
    height: calc(583rem / 16);
    background-color: #ffffff;
    border-radius: 10px;
  }

  .close{
    width: calc(36rem / 16);
    position: absolute;
    top: calc(10rem / 16);
    right: calc(10rem / 16);
  }

  .close:hover{
    cursor: pointer;
  }

  .text-wrapper{
    margin-top: calc(200rem / 16);
    text-align: center;
    font-size: calc(24rem / 16);
  }

  .text-wrapper p{
    font-family: Mtavruli;
  }

  .checkmark{
    width: calc(68rem / 16);
    display: block;
    margin-top: calc(100rem / 16);
    margin-left: auto;
    margin-right: auto;
  }


  @media(max-width: 1366px){
    html{
      font-size: 13px;
    }

    .header{
      margin-top: 30vh;
    }
  }
</style>

<script>

  const fPopup = getFailurePopup();
  const sPopup = getSuccessPopup();
  window.onclick = (e) => {
    
    if( e.target == fPopup )
    {
      closeFailurePopup();
    }

    if( e.target == sPopup ){
      closeSuccsessPopup();
    }
  }

  const CANT_PLUG_OFF = "Charger cable can't be plugged off!";
  const PLUGED_OFF    = "Charger cable is off!";

  document.getElementById( 'btn' ).addEventListener( 'click', disconnect );

  function getChargerId()
  {
    const chargerId = document.getElementById('charger_id').value;
    return chargerId;
  }
  
  function getToken()
  {
    const token = document.getElementById('token').value;
    return token;
  }

  function disconnect()
  {
    const chargerId = getChargerId();
    const token     = getToken();
    
    fetch('/disconnect', 
    {
      method: 'POST',
      headers: {
        'Content-Type': 'applicatioin/json',
      },
      body: JSON.stringify({
        chargerId,
        _token: token,
        }),
    })
      .then( response => response.json())
      .then( response => {

        if( response === CANT_PLUG_OFF)
        {
          showFailurePopup();
        }

        if( response === PLUGED_OFF )
        {
          showSuccsessPopup();
        }
        emptyInputValue();
      })
      .catch( (err) => {
        console.log( err );
      });
  }

  function showSuccsessPopup()
  {
    const successPopup = getSuccessPopup();
    successPopup.style.display = 'block';
  }
  
  function closeSuccsessPopup()
  {
    const successPopup = getSuccessPopup();
    successPopup.style.display = 'none';
  }

  function getSuccessPopup()
  {
    return document.getElementsByClassName( 'popup-underling-bg' )[0];
  }

  function showFailurePopup()
  {
    const failurePopup = getFailurePopup();
    failurePopup.style.display = 'block';
  }
  
  function closeFailurePopup()
  {
    const failurePopup = getFailurePopup();
    failurePopup.style.display = 'none';
  }

  function getFailurePopup()
  {
    return document.getElementsByClassName( 'popup-underling-bg' )[1];
  }

  function emptyInputValue()
  {
    document.getElementById('charger_id').value = '';
  }


</script>