let cart = [];

function addToCart(product) {
	const existingProduct = cart.find((item) => item.name === product.name);
	if (existingProduct) {
		existingProduct.quantity++;
	} else {
		product.quantity = 1;
		cart.push(product);
	}
	updateCart();
}

function updateCart() {
	const cartItemsContainer = document.getElementById("cart-items");
	cartItemsContainer.innerHTML = "";

	let total = 0;

	cart.forEach((item) => {
		const li = document.createElement("li");
		li.textContent = `${item.name} - Rp ${item.price} x ${item.quantity}`;
		cartItemsContainer.appendChild(li);
		total += item.price * item.quantity;
	});

	document.getElementById(
		"total-price"
	).textContent = `Total: Rp ${total.toFixed(2)}`;
	document.getElementById("cart-popup").classList.remove("hidden");
}

document.getElementById("cart-button").addEventListener("click", () => {
	const popup = document.getElementById("cart-popup");
	popup.classList.toggle("hidden");
});

document.getElementById("close-popup").addEventListener("click", () => {
	document.getElementById("cart-popup").classList.add("hidden");
});
