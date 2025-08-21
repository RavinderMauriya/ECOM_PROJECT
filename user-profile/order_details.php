<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Product Details</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f1f3f6;
      padding: 20px;
    }

    .product-container {
      display: flex;
      max-width: 1200px;
      margin: 0 auto;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 20px;
      border-radius: 8px;
      gap: 30px;
    }

    .product-image {
      width: 400px;
      height: 400px;
      object-fit: cover;
      border-radius: 8px;
      background-color: #f0f0f0;
    }

    .product-details {
      flex: 1;
    }

    .product-name {
      font-size: 24px;
      font-weight: 600;
      color: #212121;
      margin-bottom: 15px;
    }

    .product-price {
      font-size: 22px;
      color: #388e3c;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .status {
      font-size: 15px;
      color: #555;
      margin-bottom: 25px;
    }

    .product-description {
      font-size: 14px;
      color: #333;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .product-quantity {
      font-size: 16px;
      color: #555;
      margin-bottom: 15px;
    }

    .product-info-section {
      margin-top: 30px;
    }

    .section-title {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 10px;
      color: #212121;
    }

    .section-content {
      font-size: 14px;
      color: #555;
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .section-content strong {
      font-weight: bold;
    }

    .product-info-section hr {
      margin: 12px 0;
      border: 0;
      border-top: 1px solid #ddd;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .product-container {
        flex-direction: column;
        align-items: center;
      }

      .product-image {
        width: 100%;
        height: auto;
      }

      .product-details {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="product-container">
    <!-- Product Image -->
    <img src="https://img.freepik.com/free-photo/marketing-creative-collage-with-phone_23-2149379900.jpg" alt="Product Image" class="product-image" />

    <div class="product-details">
      <div class="product-name">boAt Rockerz 450 Wireless Headphones</div>
      <div class="product-price">₹1,499</div>
      <div class="status">Delivered on Apr 18, 2025</div>
      
      <!-- Product Description -->
      <div class="product-description">
        <strong>Product Description:</strong><br>
        boAt Rockerz 450 is a wireless over-ear headphone that delivers powerful sound with bass and clear audio. With Bluetooth 5.0 and a built-in mic, you can experience hassle-free music and calls. It also features adjustable headband and long-lasting battery life.
      </div>
      
      <!-- Product Quantity -->
      <div class="product-quantity">
        Quantity: <span>1</span>
      </div>

    </div>
  </div>

  <!-- Product Info Section (Price Details & Delivery Address) -->
  <div class="product-info-section">
    <div class="section-title">Price Details</div>
    <div class="section-content">
      Total: ₹1,499<br>
      Discount: ₹0<br>
      Delivery Charges : ₹0<br>
      <hr>
      <strong>Total Paid: ₹1,499</strong>
    </div>

    <div class="section-title">Delivery Address</div>
    <div class="section-content">
      Priya Mehta<br>
      102, Sapphire Residency,<br>
      Banjara Hills, Hyderabad - 500034<br>
      Phone: +91 9876543210
    </div>
  </div>

</body>
</html>
