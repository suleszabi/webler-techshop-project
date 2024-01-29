console.log("main.js loaded");

const productListDiv = document.getElementById("product_list");

const getProductList = async () => {
    let respond = await fetch(BASE_URL+"api/termek-lista");
    let product_list = await respond.json();

    let product_list_html = "";

    for(let product of product_list) {
        product_list_html += `
            <div class="col-12 col-md-6 col-xl-4 mb-4">
                <div class="bg-white shadow-sm p-3 text-center">
                    <h6 class="mb-0">${product.brand} ${product.type}</h6>
                    <p class="fst-italic text-secondary">${product.category_name}</p>
                    <div class="product-img-container">
                        <img
                        src="${BASE_URL}public/img/product_img/${product.img}"
                        alt="${product.brand} ${product.type} képe"
                        title="${product.brand} ${product.type}"
                        class="img-fluid"
                        >
                    </div>
                    <h4 class="mt-3">${product.formatted_price}</h4>
                </div>
            </div>
        `;
    }

    productListDiv.innerHTML = product_list_html;
}

getProductList();