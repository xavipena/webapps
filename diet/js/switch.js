const showMenu = (formApp, formEye) => {
	
    const dform = document.getElementById(formApp),
          iconEye = document.getElementById(formEye)
 
    iconEye.addEventListener('click', () => {
        
        //console.log("dform.display = " + dform.display);
        if (dform.display === 'block' || dform.display === 'undefined') 
        {
            dform.display = 'none'

            // Icon change
            iconEye.classList.remove('ri-eye-line')
            iconEye.classList.add('ri-eye-off-line')
        } 
        else
        {
            dform.display = 'block'

            // Icon change
            iconEye.classList.remove('ri-eye_off-line')
            iconEye.classList.add('ri-eye-line')
        }
    })
 }
 
 showMenu('formWrapper','app-eye')