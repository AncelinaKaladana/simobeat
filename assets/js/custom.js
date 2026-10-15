document.addEventListener('click',function(event){
  const dismiss=event.target.closest('[data-dismiss]');
  if(dismiss){const alert=dismiss.closest('.alert');if(alert)alert.remove();}
  const toggle=event.target.closest('[data-password-toggle]');
  if(toggle){
    const input=document.getElementById(toggle.dataset.passwordToggle);
    if(input){
      const show=input.type==='password';
      input.type=show?'text':'password';
      toggle.setAttribute('aria-label',show?'Sembunyikan kata sandi':'Tampilkan kata sandi');
      toggle.title=show?'Sembunyikan kata sandi':'Tampilkan kata sandi';
      const icon=toggle.querySelector('i');
      if(icon)icon.className=show?'bi bi-eye-slash':'bi bi-eye';
    }
  }
});
