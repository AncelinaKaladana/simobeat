document.addEventListener('click',function(event){
  const dismiss=event.target.closest('[data-dismiss]');
  if(dismiss){const alert=dismiss.closest('.alert');if(alert)alert.remove();}
});
