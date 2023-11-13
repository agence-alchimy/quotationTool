////
//// TOOL FUNCTIONS
////
const PRICE_REGEX = /^\d+([.,]\d+)?/;
const splitPriceString = (inputString) => {
  const defaultResult = [inputString, ""];

  if (inputString == null || typeof inputString !== "string")
    return defaultResult;

  let trimmedInput = inputString.trim();

  if (trimmedInput.length === 0) return defaultResult;

  let priceNumber = PRICE_REGEX.exec(trimmedInput);
  priceNumber = priceNumber != null ? priceNumber[0] : null;
  let priceText = trimmedInput.replace(priceNumber, "");
  priceText = [null, undefined, "undefined"].includes(priceText)
    ? ""
    : priceText;

  if (trimmedInput == null || priceText === trimmedInput) return defaultResult;

  return [priceNumber.trim(), `€${priceText.trimEnd()}`];
};

const rebindPriceString = (priceNumberString, priceTextString) => {
  if (
    [priceNumberString, priceTextString].some(
      (value) => value === null || value === "null"
    )
  )
    return;

  return `${priceNumberString}€ ${priceTextString}`;
};

////
//// INPUT FUNCTIONS
////

// Allow admin to copy custom post-type service desc into current quote's entry
let quoteEdit = document.querySelector("body.post-php.post-type-devis");
let invoiceEdit = document.querySelector("body.post-php.post-type-facture");
var entries;
const addActionCopy = () => {
  let copier_btns = document.querySelectorAll("div.copier");
  if (copier_btns.length > 0) {
    for (var btn of copier_btns) {
      btn.addEventListener("mousedown", (e) => {
        const elem = e.currentTarget;
        const data = {
          post: e.currentTarget.parentNode.querySelector(".acf-selection")
            .innerHTML,
          action: "retrieveDesc",
        };
        fetch(ajaxurl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Cache-Control": "no-cache",
          },
          body: new URLSearchParams(data),
        })
          .then((response) => response.json())
          .then((response) => {
            let content = response.data[0].post_content;
            let price = response.data[0].tarif;
            let title = response.data[0].post_title;
            let iframe = elem.parentNode.querySelector(
              "div.description iframe"
            ).contentDocument;
            let tarifInput = elem.parentNode.querySelector("div.tarif input");
            let titleInput = elem.parentNode.querySelector("div.titre input");
            iframe.querySelector("body").innerHTML = content;
            tarifInput.value = price;
            titleInput.value = title;
            operateTotal();
          });
      });
    }
  }
};

// Set quote's total price
const parseEntries = () => {
  entries = document.querySelectorAll(".tarif input, .nombre input, #remise");
  if (entries) {
    for (let entry of entries) {
      entry.addEventListener("keyup", (e) => {
        operateTotal();
      });
    }
  }
  let moderemise = document.querySelector("#pourcentmontant");
  if (moderemise) {
    moderemise.addEventListener("change", (e) => {
      operateTotal();
    });
  }
};

var total;
const operateTotal = () => {
  entries = document.querySelectorAll(".tarif");
  total = 0;
  let fieldNumberValue,
    fieldTextValue = "";
  for (let entry of entries) {
    let fieldValue = entry.querySelector("input").value;
    if (typeof fieldValue === "string") {
      [fieldNumberValue, fieldTextValue, ...others] =
        splitPriceString(fieldValue);
    } else {
      fieldNumberValue = fieldValue;
    }

    if (Number.isNaN(parseInt(fieldNumberValue))) {
      fieldNumberValue = 0;
    }
    // Check if quantity is set
    let nombre = entry.parentNode.querySelector(".nombre");
    let nentries = nombre.querySelector("input").value
      ? Number(nombre.querySelector("input").value)
      : 1;
    let optioncb = entry.parentNode.querySelector(
      '.option input[type="checkbox"]'
    );
    if (optioncb) {
      let option = entry.parentNode
        .querySelector('.option input[type="checkbox"]')
        .getAttribute("checked");
      if (option != "checked") {
        total = total + Number(fieldNumberValue) * nentries;
      }
    } else {
      total = total + Number(fieldNumberValue) * nentries;
    }
  }
  var remise = document.querySelector("#remise input").value;
  var typeremise = document.querySelector("#pourcentmontant select");

  var value = typeremise.options[typeremise.selectedIndex].value;

  if (value == "pourcent") {
    document.querySelector("#total_remise input").value =
      total * (remise / 100);
    total = total - total * (remise / 100);
  } else {
    document.querySelector("#total_remise input").value = remise;
    total = total - remise;
  }

  //document.querySelector('#total_remise input').value =
  document.querySelector("#total_ht input").value = total;
  document.querySelector("#total_ttc input").value = total * 1.2;
  if (invoiceEdit) {
    let acompte = document.querySelector(".acompte input").value;
    let reste_a_regler = total * 1.2 - acompte;
    document.querySelector(".total_a_regler input").value = reste_a_regler;
  }
};

const addActionAddRow = () => {
  var repeaterButtons = document.querySelectorAll(
    'a.acf-button[data-event="add-row"]'
  );
  if (repeaterButtons) {
    for (let repeaterButton of repeaterButtons) {
      repeaterButton.addEventListener("mouseup", (e) => {
        setTimeout(() => addActionCopy(), 500);
        setTimeout(() => addActionAddRow(), 500);
        setTimeout(() => parseEntries(), 500);
      });
    }
  }
};

////
//// EXECUTION
////
window.onload = () => {
  if (quoteEdit) {
    addActionCopy();
  }

  parseEntries();

  addActionAddRow();
  if (invoiceEdit || quoteEdit) {
    setInterval(() => operateTotal(), 500);
  }
  // Invoice functionnalities

  // Adds quote number to url params
  let quoteSelect = document.querySelector("#selectquote");
  if (quoteSelect) {
    quoteSelect.addEventListener("change", (e) => {
      let quoteID = e.target.options[e.target.selectedIndex].value;
      let copyQuote = document.querySelector("#copyquote");
      let url = copyQuote.getAttribute("href") + "&quote=" + quoteID;
      copyQuote.setAttribute("href", url);
    });
  }
};
