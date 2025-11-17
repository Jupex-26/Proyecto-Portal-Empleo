class Validator {
    constructor(form) {
        this.form = form;
        this.errors = {};
    }

    /**
     * Valida que un campo no esté vacío
     */
    validateRequired(fieldName, errorMessage = 'Este campo es obligatorio') {
        const field = this.form.elements[fieldName];
        
        if (!field) {
            console.error(`Campo ${fieldName} no encontrado`);
            return false;
        }

        let value = field.value.trim();
        
        if (value === '') {
            this.errors[fieldName] = errorMessage;
            return false;
        }

        delete this.errors[fieldName];
        return true;
    }


    /**
     * Valida email
     */
    validateEmail(fieldName, errorMessage = 'Email inválido') {
        if (!this.validateRequired(fieldName, 'El email es obligatorio')) {
            return false;
        }

        const field = this.form.elements[fieldName];
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let value = field.value.trim();
        
        if (!emailRegex.test(value)) {
            this.errors[fieldName] = errorMessage;
            return false;
        }

        delete this.errors[fieldName];
        return true;
    }

    /**
     * Valida contraseña
     * Por defecto: mínimo 6 caracteres
     */
    validatePassword(fieldName, options = {}) {
        const defaults = {
            minLength: 6,
            requireUppercase: false,
            requireLowercase: false,
            requireNumber: false,
            requireSpecialChar: false
        };
        
        const config = { ...defaults, ...options };
        
        if (!this.validateRequired(fieldName, 'La contraseña es obligatoria')) {
            return false;
        }

        const field = this.form.elements[fieldName];
        let value = field.value;
        
        // Validar longitud mínima
        if (value.length < config.minLength) {
            this.errors[fieldName] = `La contraseña debe tener al menos ${config.minLength} caracteres`;
            return false;
        }

        // Validar mayúscula
        if (config.requireUppercase && !/[A-Z]/.test(value)) {
            this.errors[fieldName] = 'La contraseña debe contener al menos una mayúscula';
            return false;
        }

        // Validar minúscula
        if (config.requireLowercase && !/[a-z]/.test(value)) {
            this.errors[fieldName] = 'La contraseña debe contener al menos una minúscula';
            return false;
        }

        // Validar número
        if (config.requireNumber && !/\d/.test(value)) {
            this.errors[fieldName] = 'La contraseña debe contener al menos un número';
            return false;
        }

        // Validar carácter especial
        if (config.requireSpecialChar && !/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
            this.errors[fieldName] = 'La contraseña debe contener al menos un carácter especial';
            return false;
        }

        delete this.errors[fieldName];
        return true;
    }

    /**
     * Valida fecha de nacimiento
     */
    validateFechaNacimiento(fieldName, requireMayorEdad = false, errorMessage = 'Fecha inválida') {
        if (!this.validateRequired(fieldName, 'La fecha de nacimiento es obligatoria')) {
            return false;
        }

        const field = this.form.elements[fieldName];
        let value = field.value.trim();
        const fecha = new Date(value);
        
        if (isNaN(fecha.getTime())) {
            this.errors[fieldName] = errorMessage;
            return false;
        }

        if (requireMayorEdad) {
            const hoy = new Date();
            let edad = hoy.getFullYear() - fecha.getFullYear();
            const mes = hoy.getMonth() - fecha.getMonth();
            
            if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
                edad--;
            }

            if (edad < 18) {
                this.errors[fieldName] = 'Debe ser mayor de 18 años';
                return false;
            }
        }

        delete this.errors[fieldName];
        return true;
    }

    /**
     * Valida todos los campos del formulario
     */
    validateAll() {
        this.errors = {};
        
        // Validar campos requeridos
        this.validateRequired('nombre', 'El nombre es obligatorio');
        this.validateRequired('ap1', 'El primer apellido es obligatorio');
        this.validateRequired('direccion', 'La dirección es obligatoria');
        this.validateEmail('correo');
        this.validatePassword('passwd', { minLength: 6 });
        this.validateFechaNacimiento('fecha_nacimiento', false);


        return Object.keys(this.errors).length === 0;
    }

    /**
     * Muestra los errores en el formulario
     */
    showErrors() {
        // Limpiar errores anteriores
        this.clearErrors();

        for (let fieldName in this.errors) {
            const errorMessage = this.errors[fieldName];
            
            
            // Mostrar error junto al campo
            const field = this.form.elements[fieldName];
            if (field) {
                const formGroup = field.closest('.form-group');
                if (formGroup) {
                    const errorP = document.createElement('p');
                    errorP.className = 'error-message';
                    errorP.style.color = 'red';
                    errorP.textContent = errorMessage;
                    formGroup.appendChild(errorP);
                }
            }
            
        }
    }

    /**
     * Limpia todos los mensajes de error
     */
    clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());
    }

    /**
     * Verifica si hay errores
     */
    hasErrors() {
        return Object.keys(this.errors).length > 0;
    }

    /**
     * Obtiene los errores
     */
    getErrors() {
        return this.errors;
    }
}