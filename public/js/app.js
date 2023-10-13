'use strict';
(() => {
    const buildViewModalBody = (initialBody, person) => {
        const detailsTemplate = `<p>Name: <b>__NAME__</b></p>
                    <p>Surname: <b>__SURNAME__</b></p>`;

        const body = detailsTemplate
            .replace('__NAME__', person.name)
            .replace('__SURNAME__', person.surname);
        initialBody.append(body);
        if (person.pets) {
            initialBody.append(`<h6>Pets:</h6>`);
            person.pets.forEach(pet => {
                initialBody.append(`<p>Type: ${pet.type ? pet.type : '-'}, Name: ${pet.name}</p>`);
            })
        }
    };

    $(document)
        .on('click', '#add-pet', () => {
            const petIndex = `new_${+(new Date)}_${Math.random()}`;
            $('.pets-container').append(petTemplate.split('__SEC__').join(petIndex));
        })
        .on('click', '.pets-container .remove-pet', e => $(e.currentTarget).parent('.input-group').remove())
        .on('click', '.remove-person', e => {
            $('.confirm-removal').attr('data-removable', $(e.currentTarget).data('removable'));
        })
        .on('click', '.confirm-removal', e => {
            const id = $(e.currentTarget).data('removable');
            $.ajax({
                url: `/persons/${id}`,
                type: 'DELETE',
                success: () => {
                    window.location.href = '/persons';
                }
            });
        })
        .on('click', '.view-person', e => {
            const modalBody = $('#detailsModal .modal-body');
            modalBody.empty();

            const person = $(e.currentTarget).data('person');
            buildViewModalBody(modalBody, person);
        });
})();


