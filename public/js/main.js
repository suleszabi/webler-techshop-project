console.log("main.js loaded");

const productListDiv = document.getElementById("product_list");

const createFormData = () => {
    const formData = new FormData();

    const category_filter = document.getElementById("category_filter").value;
    if(category_filter) {
        formData.append("category", category_filter);
    }

    const brand_filter = document.getElementById("brand_filter").value;
    if(brand_filter) {
        formData.append("brand", brand_filter);
    }

    const type_filter = document.getElementById("type_filter").value;
    if(type_filter) {
        formData.append("type", type_filter);
    }

    const min_price_filter = document.getElementById("min_price_filter").value;
    if(min_price_filter) {
        formData.append("min_price", min_price_filter);
    }

    const max_price_filter = document.getElementById("max_price_filter").value;
    if(max_price_filter) {
        formData.append("max_price", max_price_filter);
    }

    return formData;
}

const getProductList = async () => {
    const formData = createFormData();
    let respond = await fetch(BASE_URL+"api/termek-lista", {method: "POST", body: formData});
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
                    <a class="btn btn-sm btn-primary" href="${BASE_URL}termek?id=${product.id}">Adatlap</a>
                </div>
            </div>
        `;
    }

    productListDiv.innerHTML = product_list_html;
}

const filter_input_list = document.getElementsByClassName("filter-input");
for(let input of filter_input_list) {
    input.oninput = getProductList;
}

getProductList();