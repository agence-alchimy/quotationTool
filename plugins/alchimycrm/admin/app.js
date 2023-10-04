// let prestation = document.querySelectorAll('.prestation');
// var presta = "";
// if (prestation) {
//     // Select the node that will be observed for mutations
//     var targetNode = document.querySelector('.prestation');

//     // Options for the observer (which mutations to observe)
//     var config = { attributes: false, childList: true, characterData: false, subtree: true };

//     // Callback function to execute when mutations are observed
//     var callback = function (mutationsList) {
//         for (var mutation of mutationsList) {
//             if (mutation.type == 'childList') {
//                 //console.log('A child node has been added or removed.');
//                 //if (document.querySelector('.acf-selection')) {
//                 //console.log(mutation);
//                 if (mutation.addedNodes.length > 0) {
//                     if (mutation.addedNodes[0].className == 'acf-selection') {
//                         presta = document.querySelector('.acf-selection').innerHTML
//                     }
//                 }
//             }

//         }
//         //console.log(mutationsList)
//         console.log("presta actuelle: " + presta);
//     };

//     // Create an observer instance linked to the callback function
//     var observer = new MutationObserver(callback);

//     // Start observing the target node for configured mutations
//     observer.observe(targetNode, config);
// }


// Allow admin to copy custom post-type service desc into current quote's entry
let quoteEdit = document.querySelector('body.post-php.post-type-devis');
let invoiceEdit = document.querySelector('body.post-php.post-type-facture');
var entries;
function addActionCopy() {
    let copier_btns = document.querySelectorAll('div.copier');
    if (copier_btns.length > 0) {
        for (var btn of copier_btns) {
            btn.addEventListener('mousedown', (e) => {
                const elem = e.currentTarget
                const data = {
                    post: e.currentTarget.parentNode.querySelector('.acf-selection').innerHTML,
                    action: 'retrieveDesc'
                }
                console.log("données: " + data)
                fetch(ajaxurl, {

                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Cache-Control': 'no-cache',
                    },
                    body: new URLSearchParams(data),
                })
                    .then(response => response.json())
                    .then(response => {
                        console.log(response)
                        let content = response.data[0].post_content
                        let price = response.data[0].tarif
                        let title = response.data[0].post_title
                        let iframe = elem.parentNode.querySelector('div.description iframe').contentDocument;
                        let tarifInput = elem.parentNode.querySelector('div.tarif input')
                        let titleInput = elem.parentNode.querySelector('div.titre input')
                        iframe.querySelector('body').innerHTML = content
                        tarifInput.value = price;
                        titleInput.value = title;
                        operateTotal();

                    })


            })
        }
    }
}

if (quoteEdit) {
    addActionCopy();
}
// Set quote's total price
function parseEntries() {
    console.log('parseEntries')
    entries = document.querySelectorAll('.tarif input, .nombre input, #remise');
    if (entries) {
        for (let entry of entries) {
            entry.addEventListener('keyup', (e) => {
                console.log(parseFloat(e.currentTarget.value))
                operateTotal();
            })
        }
    }
    let moderemise = document.querySelector('#pourcentmontant');
    if (moderemise) {
        moderemise.addEventListener('change', (e) => { operateTotal() })
    }
}
parseEntries();
var total;
function operateTotal() {
    entries = document.querySelectorAll('.tarif');
    total = 0;
    for (let entry of entries) {
        let montant = entry.querySelector('input').value;
        // Check if quantity is set
        let nombre = entry.parentNode.querySelector('.nombre')
        //console.log(nombre)
        let nentries = (nombre.querySelector('input').value) ? Number(nombre.querySelector('input').value) : 1;
        //console.log(entry.value + " x " + nentries)
        let optioncb = entry.parentNode.querySelector('.option input[type="checkbox"]');
        if (optioncb) {

            let option = entry.parentNode.querySelector('.option input[type="checkbox"]').getAttribute('checked');
            //console.log(option)
            if (option != 'checked') {
                total = total + (Number(montant) * nentries);
            }
        }
        else {
            total = total + (Number(montant) * nentries);
        }
    }
    var remise = document.querySelector('#remise input').value;
    var typeremise = document.querySelector('#pourcentmontant select');

    var value = typeremise.options[typeremise.selectedIndex].value;
    console.log(value);
    if (value == 'pourcent') {
        document.querySelector('#total_remise input').value = total * (remise / 100);
        total = total - (total * (remise / 100));
    }
    else {
        document.querySelector('#total_remise input').value = remise;
        total = total - remise;
    }
    //document.querySelector('#total_remise input').value =
    document.querySelector('#total_ht input').value = total;
    document.querySelector('#total_ttc input').value = total * 1.2;
    if (invoiceEdit) {
        let acompte = document.querySelector('.acompte input').value;
        let reste_a_regler = (total * 1.2) - acompte;
        document.querySelector('.total_a_regler input').value = reste_a_regler;
    }
}

function addActionAddRow() {
    var repeaterButtons = document.querySelectorAll('a.acf-button[data-event="add-row"]')
    if (repeaterButtons) {
        for (let repeaterButton of repeaterButtons) {
            repeaterButton.addEventListener('mouseup', (e) => {

                setTimeout(addActionCopy, 500)
                setTimeout(addActionAddRow, 500)
                setTimeout(parseEntries, 500)
            })
        }
    }
}
addActionAddRow()
if (invoiceEdit || quoteEdit) {
    setInterval(operateTotal, 500);
}
// Invoice functionnalities

// Adds quote number to url params
let quoteSelect = document.querySelector('#selectquote');
if (quoteSelect) {
    quoteSelect.addEventListener('change', (e) => {
        let quoteID = e.target.options[e.target.selectedIndex].value;
        let copyQuote = document.querySelector('#copyquote');
        let url = copyQuote.getAttribute('href') + '&quote=' + quoteID;
        copyQuote.setAttribute('href', url);
    })
}

