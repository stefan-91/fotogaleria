function getOffset(el) { // vzdialenost elementu od laveho okraja okna prehliadaca
        var rect = el.getBoundingClientRect();
        return rect.left;
}

function deleteMaxElem() { // najdi a zrus zvacseny obrazok (vratane obalujuceho <div>)
        maxElem = document.getElementById('maxImg'); 
        if (maxElem !== null) {
                divElem = maxElem.parentNode;
                divElem.parentNode.removeChild(divElem);
        }
}

function ZvacsitObrazok(elem) {
        deleteMaxElem(); // ak bolo nieco uz zvacsene, najprv to zrus
        // najdi obrazok v rade najviac vpravo
        if (elem !== null) {
                src0 = elem.getAttribute("src");
                right0 = getOffset(elem);
                imid0 = elem.getAttribute("id");
                im0 = parseInt(imid0.substr(2));
                im = im0 + 1;
                while (im < im0 + 20) { // skusat max 20 id pre istotu (aby nahodou nevznikol nekonecny loop)
                        imid1 = 'id' + im;
                        elem1 = document.getElementById(imid1);
                        if (elem1 !== null) { // element sa nasiel
                                right1 = getOffset(elem1); // vid funkcia vyssie
                        }
                        else { // zrejme sme dosiahli posledny id
                                break;
                        }
                        if (right0 < right1) { // elem1 je napravo od elem0
                                imid0  = imid1;
                                right0 = right1;
                        }
                        else {
                                break;
                        }
                        im++;
                }
                // imid0 je id najpravejsieho obrazku v rade, za neho vlozime zvacseny obrazok
                elem0 = document.getElementById(imid0);
                if (elem0 !== null) {
                        newHeight = elem.naturalHeight;
                        if (newHeight > 600) {
                                newHeight = 600;
                        }
newelem = document.createElement('div');
newelem.innerHTML = '<img id=\"maxImg\" src="' + src0 + '" height="' + newHeight + '" title=\"maxImg\" onclick=\"deleteMaxElem()\"/>';
                        elem0.parentNode.insertBefore(newelem, elem0.nextSibling);
                        newelem.scrollIntoView(true);  // aby zvacseny obrazok bolo vidno
                        elem.scrollIntoView(true);     // aby bolo vidno aj povodny zmenseny obrazok
                        newelem.focus();
                }						
        }				
}
                   


