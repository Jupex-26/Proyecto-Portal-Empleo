class Modal {
  constructor(modalDiv, veloDiv) {
    this.modal = modalDiv;
    this.velo = veloDiv;
  }

  open() {
    this.velo.classList.remove('hidden');
    this.modal.classList.remove('hidden');
    this.modal.classList.add('activo');
  }

  close() {
    this.velo.classList.add('hidden');
    this.modal.classList.add('hidden');
    this.modal.classList.remove('activo');
  }
}