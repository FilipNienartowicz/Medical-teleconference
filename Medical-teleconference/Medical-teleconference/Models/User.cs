using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("Users")]
    public class User
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int UserId { get; set; }

        [Required(ErrorMessage = "Nazwa użytkownika jest wymagana!")]
        [Display(Name = "Nazwa użytkownika")]
        [StringLength(30, ErrorMessage = "Nazwa użytkownika może się składać maksymalnie z 30 znaków!")]
        public string UserName { get; set; }
        
        public ICollection<Room> Rooms { get; set; }
    }
}