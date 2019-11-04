/**
* @author Michael Gerard Galon <gigamike@gigamike.net>
*/
pragma solidity >=0.4.22 <0.7.0;

contract Escrow {
  uint balance;
  address payable public buyer;
  address payable public seller;
  address payable private escrow;
  uint private start;
  bool buyerOk;
  bool sellerOk;

  constructor(address payable buyer_address, address payable seller_address) public {
    buyer = buyer_address;
    seller = seller_address;
    escrow = msg.sender;
    start = now;
  }

  function accept() public {
    if (msg.sender == buyer){
        buyerOk = true;
    } else if (msg.sender == seller){
        sellerOk = true;
    }
    if (buyerOk && sellerOk){
        payBalance();
    } else if (buyerOk && !sellerOk && now > start + 30 days) {
      selfdestruct(msg.sender);
    }
  }

  function payBalance() private {
    escrow.transfer(address(this).balance / 100);
    if (seller.send(address(this).balance)) {
      balance = 0;
    } else {
      revert("escrow cancel");
    }
  }

  function deposit() public payable {
    if (msg.sender == buyer) {
      balance += msg.value;
    }
  }

  function cancel() public {
    if (msg.sender == buyer){
      buyerOk = false;
    } else if (msg.sender == seller){
      sellerOk = false;
    }
    if (!buyerOk && !sellerOk){
      selfdestruct(buyer);
    }
  }

  function kill() public {
    if (msg.sender == escrow) {
      selfdestruct(buyer);
    }
  }
}
