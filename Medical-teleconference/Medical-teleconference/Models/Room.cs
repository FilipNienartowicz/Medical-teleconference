using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    public class Room
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int RoomId { get; set; }
        
        [Required(ErrorMessage = "Nazwa pokoju jest wymagana!")]
        [Display(Name = "Nazwa pokoju")]
        [StringLength(50, ErrorMessage = "Nazwa pokoju może się składać maksymalnie z 30 znaków!")]
        public string RoomName { get; set; }
        
        public ICollection<User> Participants { get; set; }
        
        public ICollection<Photo> Photos { get; set; }
        
        public ICollection<Comment> Comments { get; set; }
    }
}