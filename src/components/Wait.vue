<template>
  <div class="wait">
    <div class="lds-dual-ring"></div>
  </div>
</template>

<script>
  // component: Wait
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com 

  export default {
        
  }
</script>

<style scoped>
  .wait {
    position: fixed;
    right: 40px;
    bottom: 40px;
    height: 30px;
    width: 30px;
    opacity: 0.5;
    z-index: 100;
  }
  
  .lds-dual-ring {
    display: inline-block;
    width: 30px;
    height: 30px;
  }

  .lds-dual-ring:after {
    content: " ";
    display: block;
    width: 30px;
    height: 30px;
    margin: 8px;
    border-radius: 50%;
    border: 6px solid #42b983;
    border-color: #42b983 transparent #42b983 transparent;
    animation: lds-dual-ring 1.2s linear infinite;
  }

@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>